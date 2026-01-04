<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'user_id',
        'schedule_id',
        'date',
        'check_in_at',
        'check_out_at',
        'check_in_photo',
        'check_out_photo',
        'check_in_latitude',
        'check_in_longitude',
        'check_out_latitude',
        'check_out_longitude',
        'check_in_device_info',
        'check_out_device_info',
        'status',
        'minutes_late',
        'total_minutes_worked',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'check_in_latitude' => 'decimal:8',
        'check_in_longitude' => 'decimal:8',
        'check_out_latitude' => 'decimal:8',
        'check_out_longitude' => 'decimal:8',
        'minutes_late' => 'integer',
        'total_minutes_worked' => 'integer',
    ];

    /**
     * Get the company this attendance belongs to
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user this attendance belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the schedule this attendance is associated with
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Get all alerts related to this attendance
     */
    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    /**
     * Get all logs for this attendance
     */
    public function logs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    /**
     * Get all corrections for this attendance
     */
    public function corrections()
    {
        return $this->hasMany(AttendanceCorrection::class);
    }

    /**
     * Check if attendance is checked in
     */
    public function isCheckedIn(): bool
    {
        return !is_null($this->check_in_at);
    }

    /**
     * Check if attendance is checked out
     */
    public function isCheckedOut(): bool
    {
        return !is_null($this->check_out_at);
    }
}
