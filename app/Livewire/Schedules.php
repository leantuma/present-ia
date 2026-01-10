<?php

namespace App\Livewire;

use App\Models\Schedule;
use App\Services\ScheduleService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Schedules extends Component
{
    public $schedules = [];
    public $showModal = false;
    public $editingSchedule = null;
    public $formData = [
        'name' => '',
        'type' => 'fixed',
        'start_time' => '09:00',
        'end_time' => '17:00',
        'days_of_week' => [1, 2, 3, 4, 5],
        'tolerance_minutes' => 15,
        'requires_location' => false,
        'location_latitude' => null,
        'location_longitude' => null,
        'location_radius_meters' => 100,
        'is_active' => true,
    ];

    protected $scheduleService;

    public function boot(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function mount()
    {
        $this->loadSchedules();
    }

    public function loadSchedules()
    {
        $user = Auth::user();
        $this->schedules = Schedule::where('company_id', $user->company_id)
            ->orderBy('name')
            ->get();
    }

    public function openCreateModal()
    {
        // Only Admin can create schedules
        if (!Auth::user()->isAdmin()) {
            return;
        }
        
        $this->editingSchedule = null;
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal($scheduleId)
    {
        // Only Admin can edit schedules
        if (!Auth::user()->isAdmin()) {
            return;
        }
        
        $schedule = Schedule::find($scheduleId);
        if ($schedule && $schedule->company_id === Auth::user()->company_id) {
            $this->editingSchedule = $schedule;
            $this->formData = [
                'name' => $schedule->name,
                'type' => $schedule->type,
                'start_time' => $schedule->start_time->format('H:i'),
                'end_time' => $schedule->end_time->format('H:i'),
                'days_of_week' => $schedule->days_of_week,
                'tolerance_minutes' => $schedule->tolerance_minutes,
                'requires_location' => $schedule->requires_location,
                'location_latitude' => $schedule->location_latitude,
                'location_longitude' => $schedule->location_longitude,
                'location_radius_meters' => $schedule->location_radius_meters,
                'is_active' => $schedule->is_active,
            ];
            $this->showModal = true;
        }
    }

    public function saveSchedule()
    {
        // Only Admin can save schedules
        if (!Auth::user()->isAdmin()) {
            return;
        }
        
        $this->validate([
            'formData.name' => 'required|string|max:255',
            'formData.start_time' => 'required',
            'formData.end_time' => 'required',
            'formData.days_of_week' => 'required|array|min:1',
            'formData.tolerance_minutes' => 'required|integer|min:0',
        ]);

        $user = Auth::user();
        
        if ($this->editingSchedule) {
            $this->scheduleService->updateSchedule($this->editingSchedule, $this->formData);
        } else {
            $this->scheduleService->createSchedule($user->company, $this->formData);
        }

        $this->showModal = false;
        $this->loadSchedules();
        $this->resetForm();
    }

    public function toggleActive($scheduleId)
    {
        // Only Admin can toggle active status
        if (!Auth::user()->isAdmin()) {
            return;
        }
        
        $schedule = Schedule::find($scheduleId);
        if ($schedule && $schedule->company_id === Auth::user()->company_id) {
            $schedule->update(['is_active' => !$schedule->is_active]);
            $this->loadSchedules();
        }
    }

    public function deleteSchedule($scheduleId)
    {
        // Only Admin can delete schedules
        if (!Auth::user()->isAdmin()) {
            return;
        }
        
        $schedule = Schedule::find($scheduleId);
        if ($schedule && $schedule->company_id === Auth::user()->company_id) {
            $schedule->delete();
            $this->loadSchedules();
        }
    }

    public function resetForm()
    {
        $this->formData = [
            'name' => '',
            'type' => 'fixed',
            'start_time' => '09:00',
            'end_time' => '17:00',
            'days_of_week' => [1, 2, 3, 4, 5],
            'tolerance_minutes' => 15,
            'requires_location' => false,
            'location_latitude' => null,
            'location_longitude' => null,
            'location_radius_meters' => 100,
            'is_active' => true,
        ];
    }

    public function render()
    {
        return view('livewire.schedules');
    }
}

