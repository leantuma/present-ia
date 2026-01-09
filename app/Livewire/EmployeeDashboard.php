<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\Leave;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeeDashboard extends Component
{
    public $weeklyCalendar = [];
    public $monthlyStats = [];
    public $currentWeekStart;

    public function mount()
    {
        // Initialize current week start (Monday of current week)
        $this->currentWeekStart = Carbon::now()->startOfWeek();
        $this->loadWeeklyCalendar();
        $this->loadMonthlyStats();
    }

    /**
     * Load weekly calendar data for the employee
     */
    public function loadWeeklyCalendar()
    {
        $user = Auth::user();
        
        if (!$user || !$user->isEmployee() || !$user->company) {
            $this->weeklyCalendar = [];
            return;
        }

        $weekStart = Carbon::parse($this->currentWeekStart)->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();
        
        // Get attendances for the week
        $attendancesCollection = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$weekStart, $weekEnd])
            ->get();
        
        // Group attendances by date for easy lookup
        $attendances = [];
        foreach ($attendancesCollection as $attendance) {
            $key = $attendance->date->format('Y-m-d');
            $attendances[$key] = $attendance;
        }
        
        // Get approved leaves for the week
        $leavesCollection = Leave::where('user_id', $user->id)
            ->where('status', 'approved')
            ->where(function ($query) use ($weekStart, $weekEnd) {
                $query->whereBetween('start_date', [$weekStart, $weekEnd])
                    ->orWhereBetween('end_date', [$weekStart, $weekEnd])
                    ->orWhere(function ($q) use ($weekStart, $weekEnd) {
                        $q->where('start_date', '<=', $weekStart)
                          ->where('end_date', '>=', $weekEnd);
                    });
            })
            ->get();
        
        // Build calendar structure
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $calendarDays = [];
        
        foreach ($days as $index => $dayName) {
            $currentDate = $weekStart->copy()->addDays($index);
            $dateKey = $currentDate->format('Y-m-d');
            
            // Get schedule for this day
            $schedule = $this->getScheduleForDate($user, $currentDate);
            
            // Get attendance for this day
            $attendance = $attendances[$dateKey] ?? null;
            
            // Check for leave
            $leave = $leavesCollection->first(function ($l) use ($currentDate) {
                $start = Carbon::parse($l->start_date);
                $end = Carbon::parse($l->end_date);
                return $currentDate->gte($start) && $currentDate->lte($end);
            });
            
            // Determine status and times
            $status = 'absent';
            $scheduledStart = null;
            $scheduledEnd = null;
            $actualCheckIn = null;
            $actualCheckOut = null;
            
            if ($schedule) {
                $scheduledStart = $schedule->start_time->format('H:i');
                $scheduledEnd = $schedule->end_time->format('H:i');
            }
            
            if ($leave) {
                $status = 'on_leave';
            } elseif ($attendance) {
                $status = $attendance->status ?? 'present';
                $actualCheckIn = $attendance->check_in_at ? $attendance->check_in_at->format('H:i') : null;
                $actualCheckOut = $attendance->check_out_at ? $attendance->check_out_at->format('H:i') : null;
            }
            
            $calendarDays[$dayName] = [
                'date' => $currentDate,
                'scheduled_start' => $scheduledStart,
                'scheduled_end' => $scheduledEnd,
                'actual_check_in' => $actualCheckIn,
                'actual_check_out' => $actualCheckOut,
                'status' => $status,
                'attendance' => $attendance,
                'leave' => $leave,
            ];
        }
        
        $this->weeklyCalendar = [
            'week_start' => $weekStart,
            'week_end' => $weekEnd,
            'days' => $calendarDays,
        ];
    }

    /**
     * Get schedule for a specific date
     */
    private function getScheduleForDate($user, Carbon $date): ?Schedule
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
     * Load monthly statistics
     */
    public function loadMonthlyStats()
    {
        $user = Auth::user();
        
        if (!$user || !$user->isEmployee()) {
            $this->monthlyStats = [
                'hours_worked' => 0,
                'times_late' => 0,
                'times_absent' => 0,
                'days_worked' => 0,
            ];
            return;
        }

        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();
        
        // Get all attendances for the current month
        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->get();
        
        // Calculate statistics
        $totalMinutes = $attendances->sum('total_minutes_worked') ?? 0;
        $hoursWorked = round($totalMinutes / 60, 2);
        
        $timesLate = $attendances->where('status', 'late')->count();
        $timesAbsent = $attendances->where('status', 'absent')->count();
        $daysWorked = $attendances->whereNotNull('check_in_at')->count();
        
        $this->monthlyStats = [
            'hours_worked' => $hoursWorked,
            'times_late' => $timesLate,
            'times_absent' => $timesAbsent,
            'days_worked' => $daysWorked,
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
        $this->currentWeekStart = Carbon::now()->startOfWeek();
        $this->loadWeeklyCalendar();
    }

    public function render()
    {
        return view('livewire.employee-dashboard');
    }
}

