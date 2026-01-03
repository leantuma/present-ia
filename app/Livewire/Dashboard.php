<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\Alert;
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

    protected $aiService;

    public function boot(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $user = Auth::user();
        $company = $user->company;

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
        $alert = Alert::find($alertId);
        if ($alert && $alert->company_id === Auth::user()->company_id) {
            $alert->markAsRead();
            $this->loadDashboardData();
        }
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}

