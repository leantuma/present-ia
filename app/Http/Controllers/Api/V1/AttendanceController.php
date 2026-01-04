<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Check-in
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'nullable|string', // base64
            'device_id' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();

        if (!$this->attendanceService->canCheckIn($user)) {
            return response()->json([
                'message' => 'Ya has realizado check-in hoy',
            ], 400);
        }

        try {
            $data = [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'device_id' => $request->device_id,
                'device_info' => [
                    'user_agent' => $request->userAgent(),
                    'platform' => $request->header('X-Platform', 'unknown'),
                ],
                'notes' => $request->notes,
            ];

            if ($request->photo) {
                $data['photo'] = $request->photo;
            }

            $attendance = $this->attendanceService->checkIn($user, $data);

            return response()->json([
                'message' => 'Check-in realizado exitosamente',
                'attendance' => $attendance->load('schedule'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Check-out
     */
    public function checkOut(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'nullable|string',
            'device_id' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();

        if (!$this->attendanceService->canCheckOut($user)) {
            return response()->json([
                'message' => 'No puedes hacer check-out sin haber hecho check-in',
            ], 400);
        }

        try {
            $data = [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'device_id' => $request->device_id,
                'device_info' => [
                    'user_agent' => $request->userAgent(),
                    'platform' => $request->header('X-Platform', 'unknown'),
                ],
                'notes' => $request->notes,
            ];

            if ($request->photo) {
                $data['photo'] = $request->photo;
            }

            $attendance = $this->attendanceService->checkOut($user, $data);

            return response()->json([
                'message' => 'Check-out realizado exitosamente',
                'attendance' => $attendance->load('schedule'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Obtener asistencia de hoy
     */
    public function today(Request $request)
    {
        $user = Auth::user();
        $attendance = $this->attendanceService->getTodayAttendance($user);

        return response()->json([
            'attendance' => $attendance?->load('schedule'),
            'can_check_in' => $this->attendanceService->canCheckIn($user),
            'can_check_out' => $this->attendanceService->canCheckOut($user),
        ]);
    }

    /**
     * Historial de asistencias
     */
    public function history(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $user = Auth::user();
        $query = Attendance::where('user_id', $user->id)
            ->where('company_id', $user->company_id);

        if ($request->start_date) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->where('date', '<=', $request->end_date);
        }

        $attendances = $query->orderBy('date', 'desc')
            ->with('schedule')
            ->paginate($request->per_page ?? 15);

        return response()->json($attendances);
    }
}
