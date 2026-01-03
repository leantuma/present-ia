<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'attendance_id',
        'user_id',
        'action',
        'ip_address',
        'user_agent',
        'device_id',
        'latitude',
        'longitude',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'metadata' => 'array',
    ];

    /**
     * Get the company this log belongs to
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the attendance this log belongs to
     */
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    /**
     * Get the user this log belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the device this log belongs to
     */
    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'device_id');
    }
}
