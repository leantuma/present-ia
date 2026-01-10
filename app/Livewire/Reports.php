<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Services\TimezoneService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Reports extends Component
{
    public $startDate;
    public $endDate;
    public $selectedUserId = null;
    public $attendances = [];
    public $summary = [];

    protected $timezoneService;

    public function boot(TimezoneService $timezoneService)
    {
        $this->timezoneService = $timezoneService;
    }

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
        $this->loadReports();
    }

    public function loadReports()
    {
        $user = Auth::user();
        $company = $user->company;

        $query = Attendance::where('company_id', $company->id)
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->with('user', 'schedule')
            ->orderBy('date', 'desc')
            ->orderBy('check_in_at', 'desc');

        if ($this->selectedUserId) {
            $query->where('user_id', $this->selectedUserId);
        } elseif ($user->isEmployee()) {
            $query->where('user_id', $user->id);
        }

        $this->attendances = $query->get();

        // Calculate summary
        $this->summary = [
            'total_days' => $this->attendances->count(),
            'present' => $this->attendances->where('status', 'present')->count(),
            'late' => $this->attendances->where('status', 'late')->count(),
            'absent' => $this->attendances->where('status', 'absent')->count(),
            'total_hours' => round($this->attendances->sum('total_minutes_worked') / 60, 2),
            'average_minutes_late' => round($this->attendances->where('status', 'late')->avg('minutes_late') ?? 0, 2),
        ];
    }

    public function updatedStartDate()
    {
        $this->loadReports();
    }

    public function updatedEndDate()
    {
        $this->loadReports();
    }

    public function updatedSelectedUserId()
    {
        $this->loadReports();
    }

    public function exportExcel()
    {
        // TODO: Implement Excel export using Laravel Excel or similar
        return redirect()->back()->with('message', __('reports.excel_coming_soon'));
    }

    public function exportPdf()
    {
        // TODO: Implement PDF export using DomPDF or similar
        return redirect()->back()->with('message', __('reports.pdf_coming_soon'));
    }

    public function render()
    {
        $user = Auth::user();
        $company = $user->company;

        $users = $company->users()
            ->where('role', 'employee')
            ->orderBy('name')
            ->get();

        return view('livewire.reports', [
            'users' => $users,
        ]);
    }
}

