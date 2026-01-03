<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'user_id' => null,
            'name' => $this->faker->randomElement(['Standard Shift', 'Morning Shift', 'Evening Shift', 'Flexible Hours']),
            'type' => 'fixed',
            'start_time' => $this->faker->time('H:i', '09:00'),
            'end_time' => $this->faker->time('H:i', '17:00'),
            'days_of_week' => [1, 2, 3, 4, 5], // Monday to Friday
            'tolerance_minutes' => 15,
            'requires_location' => false,
            'location_latitude' => null,
            'location_longitude' => null,
            'location_radius_meters' => 100,
            'is_active' => true,
        ];
    }
}
