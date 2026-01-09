<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'logo',
        'is_active',
        'language',
        'settings',
        'qr_token',
        'qr_token_expires_at',
        'fixed_qr_token',
        'qr_login_enabled',
        'geolocation_required',
        'geolocation_radius_meters',
        'geolocation_latitude',
        'geolocation_longitude',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
        'qr_token_expires_at' => 'datetime',
        'qr_login_enabled' => 'boolean',
        'geolocation_required' => 'boolean',
        'geolocation_radius_meters' => 'decimal:2',
        'geolocation_latitude' => 'decimal:8',
        'geolocation_longitude' => 'decimal:8',
    ];

    /**
     * Get all users belonging to this company
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all employee profiles for this company
     */
    public function employeeProfiles()
    {
        return $this->hasMany(EmployeeProfile::class);
    }

    /**
     * Get all schedules for this company
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get all attendances for this company
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get all alerts for this company
     */
    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    /**
     * Get the subscription for this company
     */
    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    /**
     * Get all devices for this company
     */
    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    /**
     * Get all attendance logs for this company
     */
    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    /**
     * Get all attendance corrections for this company
     */
    public function attendanceCorrections()
    {
        return $this->hasMany(AttendanceCorrection::class);
    }

    /**
     * Get all leaves for this company
     */
    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    /**
     * Check if QR login is enabled
     */
    public function isQrLoginEnabled(): bool
    {
        return $this->qr_login_enabled ?? true;
    }

    /**
     * Check if geolocation is required
     */
    public function isGeolocationRequired(): bool
    {
        return $this->geolocation_required ?? false;
    }

    /**
     * Get the language for this company
     */
    public function getLanguage(): string
    {
        return $this->language ?? 'es';
    }
}
