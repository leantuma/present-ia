<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;

class ScheduleService
{
    /**
     * Create a new schedule
     *
     * @param Company $company
     * @param array $data
     * @return Schedule
     */
    public function createSchedule(Company $company, array $data): Schedule
    {
        return Schedule::create([
            'company_id' => $company->id,
            'user_id' => $data['user_id'] ?? null,
            'name' => $data['name'],
            'type' => $data['type'] ?? 'fixed',
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'days_of_week' => $data['days_of_week'] ?? [1, 2, 3, 4, 5], // Default Mon-Fri
            'tolerance_minutes' => $data['tolerance_minutes'] ?? 15,
            'requires_location' => $data['requires_location'] ?? false,
            'location_latitude' => $data['location_latitude'] ?? null,
            'location_longitude' => $data['location_longitude'] ?? null,
            'location_radius_meters' => $data['location_radius_meters'] ?? 100,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    /**
     * Update an existing schedule
     *
     * @param Schedule $schedule
     * @param array $data
     * @return Schedule
     */
    public function updateSchedule(Schedule $schedule, array $data): Schedule
    {
        $schedule->update($data);
        return $schedule->fresh();
    }

    /**
     * Get active schedules for a user on a specific date
     *
     * @param User $user
     * @param Carbon $date
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveSchedulesForDate(User $user, Carbon $date)
    {
        $dayOfWeek = $date->dayOfWeek; // 0-6, Sunday-Saturday
        $dayOfWeek = $dayOfWeek === 0 ? 7 : $dayOfWeek; // Convert to 1-7

        return Schedule::where('company_id', $user->company_id)
            ->where('is_active', true)
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhereNull('user_id');
            })
            ->get()
            ->filter(function ($schedule) use ($dayOfWeek) {
                return in_array($dayOfWeek, $schedule->days_of_week ?? []);
            });
    }

    /**
     * Deactivate a schedule
     *
     * @param Schedule $schedule
     * @return Schedule
     */
    public function deactivateSchedule(Schedule $schedule): Schedule
    {
        $schedule->update(['is_active' => false]);
        return $schedule->fresh();
    }
}

