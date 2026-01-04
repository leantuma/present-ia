<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\Alert;
use App\Models\Leave;
use App\Models\Company;
use App\Models\User;
use App\Services\AIService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $todayStats = [];
    public $recentAlerts = [];
    public $weeklySummary = [];
    public $recentAttendances = [];
    public $currentWeekStart;
    public $weeklyCalendar = [];
    
    // Superadmin dashboard data
    public $superadminStats = [];
    public $companiesList = [];

    protected $aiService;

    public function boot(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function mount()
    {
        // Initialize current week start (Monday of current week)
        $this->currentWeekStart = Carbon::now()->startOfWeek();
        $this->loadDashboardData();
        
        // Load weekly calendar for admin/supervisor
        if (Auth::user() && (Auth::user()->isAdmin() || Auth::user()->isSupervisor())) {
            $this->loadWeeklyCalendar();
        }
    }

    public function loadDashboardData()
    {
        $user = Auth::user();
        
        // Handle superadmin case (no company)
        if ($user->isSuperAdmin()) {
            $this->loadSuperadminData();
            return;
        }

        $company = $user->company;

        // Ensure company exists (safety check)
        if (!$company) {
            $this->todayStats = [
                'total_employees' => 0,
                'checked_in' => 0,
                'late' => 0,
                'absent' => 0,
            ];
            $this->recentAlerts = collect();
            $this->weeklySummary = [];
            $this->recentAttendances = collect();
            return;
        }

        // Today's stats
        $today = Carbon::today();
        $this->todayStats = [
            'total_employees' => $company->users()->where('role', 'employee')->count(),
            'checked_in' => Attendance::where('company_id', $company->id)
                ->where('date', $today)
                ->whereNotNull('check_in_at')
                ->count(),
            'late' => Attendance::where('company_id', $company->id)
                ->where('date', $today)
                ->where('status', 'late')
                ->count(),
            'absent' => Attendance::where('company_id', $company->id)
                ->where('date', $today)
                ->where('status', 'absent')
                ->count(),
        ];

        // Recent alerts
        $query = Alert::where('company_id', $company->id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(10);

        if ($user->isEmployee()) {
            $query->where('user_id', $user->id);
        }

        $this->recentAlerts = $query->get();

        // Weekly summary (for admins/supervisors)
        if ($user->isAdmin() || $user->isSupervisor()) {
            $this->weeklySummary = $this->aiService->generateWeeklySummary($company);
        }

        // Recent attendances
        $attendanceQuery = Attendance::where('company_id', $company->id)
            ->orderBy('date', 'desc')
            ->orderBy('check_in_at', 'desc')
            ->limit(10)
            ->with('user');

        if ($user->isEmployee()) {
            $attendanceQuery->where('user_id', $user->id);
        }

        $this->recentAttendances = $attendanceQuery->get();
    }

    public function markAlertAsRead($alertId)
    {
        $user = Auth::user();
        
        // Superadmin can't mark alerts as read (they don't belong to a company)
        if ($user->isSuperAdmin()) {
            return;
        }
        
        $alert = Alert::find($alertId);
        if ($alert && $alert->company_id === $user->company_id) {
            $alert->markAsRead();
            $this->loadDashboardData();
        }
    }

    /**
     * Load weekly calendar data
     */
    public function loadWeeklyCalendar()
    {
        $user = Auth::user();
        
        // Only for admin and supervisor
        if (!$user || (!$user->isAdmin() && !$user->isSupervisor())) {
            $this->weeklyCalendar = [];
            return;
        }
        
        // Handle superadmin case
        if ($user->isSuperAdmin() || !$user->company) {
            $this->weeklyCalendar = [];
            return;
        }
        
        $company = $user->company;
        $weekStart = Carbon::parse($this->currentWeekStart)->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();
        
        // Get all employees
        $employees = $company->users()
            ->where('role', 'employee')
            ->orderBy('name')
            ->get();
        
        // Get all attendances for the week
        $attendancesCollection = Attendance::where('company_id', $company->id)
            ->whereBetween('date', [$weekStart, $weekEnd])
            ->with('user')
            ->get();
        
        // Group attendances by user_id and date for easy lookup
        $attendances = [];
        foreach ($attendancesCollection as $attendance) {
            $key = $attendance->user_id . '_' . $attendance->date->format('Y-m-d');
            $attendances[$key] = $attendance;
        }
        
        // Get all approved leaves for the week
        $leavesCollection = Leave::where('company_id', $company->id)
            ->where('status', 'approved')
            ->where(function ($query) use ($weekStart, $weekEnd) {
                $query->whereBetween('start_date', [$weekStart, $weekEnd])
                    ->orWhereBetween('end_date', [$weekStart, $weekEnd])
                    ->orWhere(function ($q) use ($weekStart, $weekEnd) {
                        $q->where('start_date', '<=', $weekStart)
                          ->where('end_date', '>=', $weekEnd);
                    });
            })
            ->with('user')
            ->get();
        
        // Build calendar structure
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $calendarDays = [];
        
        foreach ($days as $index => $dayName) {
            $currentDate = $weekStart->copy()->addDays($index);
            $calendarDays[$dayName] = [
                'date' => $currentDate,
                'employees' => [],
            ];
            
            foreach ($employees as $employee) {
                // Check for attendance
                $attendanceKey = $employee->id . '_' . $currentDate->format('Y-m-d');
                $attendance = $attendances[$attendanceKey] ?? null;
                
                // Check for leave
                $leave = $leavesCollection->first(function ($l) use ($employee, $currentDate) {
                    if ($l->user_id !== $employee->id) {
                        return false;
                    }
                    $start = Carbon::parse($l->start_date);
                    $end = Carbon::parse($l->end_date);
                    return $currentDate->gte($start) && $currentDate->lte($end);
                });
                
                // Determine status
                $status = 'absent';
                $checkIn = null;
                $checkOut = null;
                
                if ($leave) {
                    $status = 'on_leave';
                } elseif ($attendance) {
                    $status = $attendance->status ?? 'present';
                    $checkIn = $attendance->check_in_at ? $attendance->check_in_at->format('H:i') : null;
                    $checkOut = $attendance->check_out_at ? $attendance->check_out_at->format('H:i') : null;
                }
                
                $calendarDays[$dayName]['employees'][] = [
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                    'attendance' => $attendance,
                    'leave' => $leave,
                    'status' => $status,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                ];
            }
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
        $this->currentWeekStart = Carbon::now()->startOfWeek();
        $this->loadWeeklyCalendar();
    }

    /**
     * Load superadmin dashboard data
     */
    public function loadSuperadminData()
    {
        // Get all companies
        $companies = Company::withCount(['users' => function ($query) {
            $query->where('role', 'employee');
        }])->orderBy('created_at', 'desc')->get();
        
        // Calculate statistics
        $totalCompanies = $companies->count();
        $activeCompanies = $companies->where('is_active', true)->count();
        $inactiveCompanies = $companies->where('is_active', false)->count();
        $totalEmployees = $companies->sum('users_count');
        
        $this->superadminStats = [
            'total_companies' => $totalCompanies,
            'active_companies' => $activeCompanies,
            'inactive_companies' => $inactiveCompanies,
            'total_employees' => $totalEmployees,
        ];
        
        // Prepare companies list with employee count
        $this->companiesList = $companies->map(function ($company) {
            return [
                'id' => $company->id,
                'name' => $company->name,
                'is_active' => $company->is_active,
                'employees_count' => $company->users_count,
                'created_at' => $company->created_at,
            ];
        })->toArray();
        
        // Set empty stats for compatibility
        $this->todayStats = [
            'total_employees' => 0,
            'checked_in' => 0,
            'late' => 0,
            'absent' => 0,
        ];
        $this->recentAlerts = collect();
        $this->weeklySummary = [];
        $this->recentAttendances = collect();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}

