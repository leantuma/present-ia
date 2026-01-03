<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'user_id',
        'name',
        'type',
        'start_time',
        'end_time',
        'days_of_week',
        'tolerance_minutes',
        'requires_location',
        'location_latitude',
        'location_longitude',
        'location_radius_meters',
        'is_active',
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'requires_location' => 'boolean',
        'location_latitude' => 'decimal:8',
        'location_longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    /**
     * Get the company this schedule belongs to
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user this schedule is assigned to (null = company-wide)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all attendances for this schedule
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Check if schedule is active for a specific day of week (1-7, Monday-Sunday)
     */
    public function isActiveForDay(int $dayOfWeek): bool
    {
        return in_array($dayOfWeek, $this->days_of_week ?? []);
    }
}
