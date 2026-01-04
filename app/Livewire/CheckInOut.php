<?php

namespace App\Livewire;

use App\Services\AttendanceService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

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

    protected $attendanceService;

    public function boot(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function mount()
    {
        $this->loadTodayAttendance();
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

    public function render()
    {
        return view('livewire.check-in-out');
    }
}

