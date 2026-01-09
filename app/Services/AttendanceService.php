<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttendanceService
{
    /**
     * Check in an employee
     *
     * @param User $user
     * @param array $data Contains: photo (base64), latitude, longitude, device_info
     * @return Attendance
     */
    public function checkIn(User $user, array $data): Attendance
    {
        $today = Carbon::today();
        
        // Check if already checked in today
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->whereNotNull('check_in_at')
            ->first();

        if ($existingAttendance) {
            throw new \Exception('Already checked in today');
        }

        // Get or create today's attendance record
        $attendance = Attendance::firstOrCreate(
            [
                'company_id' => $user->company_id,
                'user_id' => $user->id,
                'date' => $today,
            ],
            [
                'status' => 'present',
            ]
        );

        // Find applicable schedule
        $schedule = $this->findApplicableSchedule($user, $today);
        if ($schedule) {
            $attendance->schedule_id = $schedule->id;
            
            // Check if late
            $startTime = Carbon::parse($today->format('Y-m-d') . ' ' . $schedule->start_time->format('H:i:s'));
            $toleranceEnd = $startTime->copy()->addMinutes($schedule->tolerance_minutes);
            $now = Carbon::now();

            if ($now->gt($toleranceEnd)) {
                $attendance->status = 'late';
                $attendance->minutes_late = $now->diffInMinutes($toleranceEnd);
            }

            // Validate geolocation if required
            if ($schedule->requires_location) {
                $this->validateGeolocation(
                    $data['latitude'] ?? null,
                    $data['longitude'] ?? null,
                    $schedule->location_latitude,
                    $schedule->location_longitude,
                    $schedule->location_radius_meters
                );
            }
        }

        // Save photo if provided
        if (isset($data['photo'])) {
            $attendance->check_in_photo = $this->savePhoto($data['photo'], $user, 'check_in');
        }

        // Save check-in data
        $attendance->check_in_at = Carbon::now();
        $attendance->check_in_latitude = $data['latitude'] ?? null;
        $attendance->check_in_longitude = $data['longitude'] ?? null;
        $attendance->check_in_device_info = json_encode($data['device_info'] ?? []);
        $attendance->save();

        // Create log entry
        $this->createLog($attendance, 'check_in', $data);

        return $attendance;
    }

    /**
     * Check out an employee
     *
     * @param User $user
     * @param array $data Contains: photo (base64), latitude, longitude, device_info
     * @return Attendance
     */
    public function checkOut(User $user, array $data): Attendance
    {
        $today = Carbon::today();
        
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->whereNotNull('check_in_at')
            ->whereNull('check_out_at')
            ->first();

        if (!$attendance) {
            throw new \Exception('No check-in found for today');
        }

        // Save photo if provided
        if (isset($data['photo'])) {
            $attendance->check_out_photo = $this->savePhoto($data['photo'], $user, 'check_out');
        }

        // Calculate total minutes worked
        $checkOutTime = Carbon::now();
        $attendance->check_out_at = $checkOutTime;
        $attendance->check_out_latitude = $data['latitude'] ?? null;
        $attendance->check_out_longitude = $data['longitude'] ?? null;
        $attendance->check_out_device_info = json_encode($data['device_info'] ?? []);
        
        if ($attendance->check_in_at) {
            $attendance->total_minutes_worked = $checkOutTime->diffInMinutes($attendance->check_in_at);
        }

        $attendance->save();

        // Create log entry
        $this->createLog($attendance, 'check_out', $data);

        return $attendance;
    }

    /**
     * Find applicable schedule for user on a given date
     */
    private function findApplicableSchedule(User $user, Carbon $date): ?Schedule
    {
        $dayOfWeek = $date->dayOfWeek; // 0-6, Sunday-Saturday
        // Convert to 1-7 (Monday-Sunday) for our system
        $dayOfWeek = $dayOfWeek === 0 ? 7 : $dayOfWeek;

        // First try user-specific schedule
        $schedule = Schedule::where('company_id', $user->company_id)
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->get()
            ->first(function ($schedule) use ($dayOfWeek) {
                return in_array($dayOfWeek, $schedule->days_of_week ?? []);
            });

        // If no user-specific schedule, try company-wide
        if (!$schedule) {
            $schedule = Schedule::where('company_id', $user->company_id)
                ->whereNull('user_id')
                ->where('is_active', true)
                ->get()
                ->first(function ($schedule) use ($dayOfWeek) {
                    return in_array($dayOfWeek, $schedule->days_of_week ?? []);
                });
        }

        return $schedule;
    }

    /**
     * Validate geolocation against schedule requirements
     */
    private function validateGeolocation(?float $lat, ?float $lng, ?float $requiredLat, ?float $requiredLng, int $radiusMeters): void
    {
        if (!$lat || !$lng || !$requiredLat || !$requiredLng) {
            throw new \Exception('Geolocation data is required');
        }

        $distance = $this->calculateDistance($lat, $lng, $requiredLat, $requiredLng);
        
        if ($distance > $radiusMeters) {
            throw new \Exception("Location is outside the allowed radius. Distance: {$distance}m, Allowed: {$radiusMeters}m");
        }
    }

    /**
     * Calculate distance between two coordinates in meters (Haversine formula)
     */
    private function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000; // Earth radius in meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Save photo from base64 string
     */
    private function savePhoto(string $base64Photo, User $user, string $type): string
    {
        // Remove data URL prefix if present
        $base64Photo = preg_replace('/^data:image\/\w+;base64,/', '', $base64Photo);
        $imageData = base64_decode($base64Photo);

        if ($imageData === false) {
            throw new \Exception('Invalid image data');
        }

        $filename = $user->id . '_' . $type . '_' . time() . '.jpg';
        $path = 'attendance-photos/' . $user->company_id . '/' . $filename;

        Storage::disk('public')->put($path, $imageData);

        return $path;
    }

    /**
     * Get today's attendance status for a user
     */
    public function getTodayAttendance(User $user): ?Attendance
    {
        return Attendance::where('user_id', $user->id)
            ->where('date', Carbon::today())
            ->first();
    }

    /**
     * Create an attendance log entry
     */
    private function createLog(Attendance $attendance, string $action, array $data): void
    {
        AttendanceLog::create([
            'company_id' => $attendance->company_id,
            'attendance_id' => $attendance->id,
            'user_id' => $attendance->user_id,
            'action' => $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'device_id' => $data['device_id'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'notes' => $data['notes'] ?? null,
            'metadata' => $data['metadata'] ?? null,
        ]);
    }

    /**
     * Prevent double check-in/out
     */
    public function canCheckIn(User $user): bool
    {
        $todayAttendance = $this->getTodayAttendance($user);
        return !$todayAttendance || !$todayAttendance->check_in_at;
    }

    public function canCheckOut(User $user): bool
    {
        $todayAttendance = $this->getTodayAttendance($user);
        return $todayAttendance && 
               $todayAttendance->check_in_at && 
               !$todayAttendance->check_out_at;
    }

    /**
     * Validate fixed QR token for check-in
     * This can be used to verify that the user is checking in at the correct location
     *
     * @param string $qrToken
     * @param int $companyId
     * @return bool
     */
    public function validateFixedQRForCheckIn(string $qrToken, int $companyId): bool
    {
        $qrService = app(\App\Services\QRService::class);
        $company = $qrService->validateFixedQRToken($qrToken, $companyId);
        
        return $company !== null;
    }
}

