<?php

namespace App\Livewire;

use App\Services\AttendanceService;
use App\Services\TimezoneService;
use App\Models\Attendance;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckInOut extends Component
{
    use WithFileUploads;

    public $todayAttendance;
    public $canCheckIn = false;
    public $canCheckOut = false;
    public $latitude;
    public $longitude;
    public $photo;
    public $errorMessage = '';
    public $successMessage = '';
    public $weeklyCalendar = [];
    public $currentWeekStart;

    protected $attendanceService;
    protected $timezoneService;

    public function boot(AttendanceService $attendanceService, TimezoneService $timezoneService)
    {
        $this->attendanceService = $attendanceService;
        $this->timezoneService = $timezoneService;
    }

    public function mount()
    {
        $user = Auth::user();
        $company = $user->company ?? null;
        $timezoneService = app(TimezoneService::class);
        $this->currentWeekStart = $timezoneService->now($company)->startOfWeek();
        $this->loadTodayAttendance();
        $this->loadWeeklyCalendar();
        $this->getCurrentLocation();
    }

    public function loadTodayAttendance()
    {
        $this->todayAttendance = $this->attendanceService->getTodayAttendance(Auth::user());
        $this->canCheckIn = !$this->todayAttendance || !$this->todayAttendance->check_in_at;
        $this->canCheckOut = $this->todayAttendance && $this->todayAttendance->check_in_at && !$this->todayAttendance->check_out_at;
    }

    public function getCurrentLocation()
    {
        // This will be called via JavaScript in the view
        $this->dispatch('request-location');
    }

    public function setLocation($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
    }

    public function checkIn()
    {
        try {
            $this->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

            $deviceInfo = [
                'user_agent' => request()->userAgent(),
                'platform' => $this->getPlatform(),
            ];

            $data = [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'device_info' => $deviceInfo,
            ];

            if ($this->photo) {
                $data['photo'] = base64_encode(file_get_contents($this->photo->getRealPath()));
            }

            $this->attendanceService->checkIn(Auth::user(), $data);
            
            $this->successMessage = 'Successfully checked in!';
            $this->errorMessage = '';
            $this->loadTodayAttendance();
            $this->loadWeeklyCalendar();
            $this->reset(['photo']);
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
            $this->successMessage = '';
        }
    }

    public function checkOut()
    {
        try {
            $this->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

            $deviceInfo = [
                'user_agent' => request()->userAgent(),
                'platform' => $this->getPlatform(),
            ];

            $data = [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'device_info' => $deviceInfo,
            ];

            if ($this->photo) {
                $data['photo'] = base64_encode(file_get_contents($this->photo->getRealPath()));
            }

            $this->attendanceService->checkOut(Auth::user(), $data);
            
            $this->successMessage = 'Successfully checked out!';
            $this->errorMessage = '';
            $this->loadTodayAttendance();
            $this->loadWeeklyCalendar();
            $this->reset(['photo']);
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
            $this->successMessage = '';
        }
    }

    private function getPlatform()
    {
        $userAgent = request()->userAgent();
        if (preg_match('/mobile/i', $userAgent)) {
            return 'mobile';
        }
        return 'web';
    }

    /**
     * Load weekly calendar data
     */
    public function loadWeeklyCalendar()
    {
        $user = Auth::user();
        
        if (!$user || !$user->company) {
            $this->weeklyCalendar = [];
            return;
        }

        $company = $user->company;
        $weekStart = Carbon::parse($this->currentWeekStart)->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();
        
        // Get attendances for the week
        $attendancesCollection = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$weekStart->format('Y-m-d'), $weekEnd->format('Y-m-d')])
            ->get();
        
        // Group attendances by date for easy lookup
        $attendances = [];
        foreach ($attendancesCollection as $attendance) {
            $key = $attendance->date->format('Y-m-d');
            $attendances[$key] = $attendance;
        }
        
        // Build calendar structure
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $calendarDays = [];
        
        foreach ($days as $index => $dayName) {
            $currentDate = $weekStart->copy()->addDays($index);
            $dateKey = $currentDate->format('Y-m-d');
            
            // Get attendance for this day
            $attendance = $attendances[$dateKey] ?? null;
            
            // Determine times
            $checkIn = null;
            $checkOut = null;
            $status = null;
            
            if ($attendance) {
                $status = $attendance->status ?? 'present';
                $checkIn = $attendance->check_in_at ? $this->timezoneService->formatForCompany($company, $attendance->check_in_at, 'H:i') : null;
                $checkOut = $attendance->check_out_at ? $this->timezoneService->formatForCompany($company, $attendance->check_out_at, 'H:i') : null;
            }
            
            $calendarDays[$dayName] = [
                'date' => $currentDate,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'status' => $status,
                'attendance' => $attendance,
            ];
        }
        
        $this->weeklyCalendar = [
            'week_start' => $weekStart,
            'week_end' => $weekEnd,
            'days' => $calendarDays,
        ];
    }

    /**
     * Navigate to previous week
     */
    public function previousWeek()
    {
        $this->currentWeekStart = Carbon::parse($this->currentWeekStart)->subWeek();
        $this->loadWeeklyCalendar();
    }

    /**
     * Navigate to next week
     */
    public function nextWeek()
    {
        $this->currentWeekStart = Carbon::parse($this->currentWeekStart)->addWeek();
        $this->loadWeeklyCalendar();
    }

    /**
     * Go to current week
     */
    public function goToCurrentWeek()
    {
        $user = Auth::user();
        $company = $user->company ?? null;
        $this->currentWeekStart = $this->timezoneService->now($company)->startOfWeek();
        $this->loadWeeklyCalendar();
    }

    public function render()
    {
        return view('livewire.check-in-out');
    }
}

