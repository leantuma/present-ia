<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'role',
        'pin',
        'pin_set_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'pin_set_at' => 'datetime',
    ];

    /**
     * Get the company this user belongs to
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the employee profile for this user
     */
    public function employeeProfile()
    {
        return $this->hasOne(EmployeeProfile::class);
    }

    /**
     * Get all schedules for this user
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get all attendances for this user
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get all alerts for this user
     */
    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is supervisor
     */
    public function isSupervisor(): bool
    {
        return $this->role === 'supervisor';
    }

    /**
     * Check if user is employee
     */
    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    /**
     * Check if user is superadmin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * Get all devices for this user
     */
    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    /**
     * Get all attendance logs for this user
     */
    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    /**
     * Get all attendance corrections requested by this user
     */
    public function requestedCorrections()
    {
        return $this->hasMany(AttendanceCorrection::class, 'requested_by');
    }

    /**
     * Get all attendance corrections reviewed by this user
     */
    public function reviewedCorrections()
    {
        return $this->hasMany(AttendanceCorrection::class, 'reviewed_by');
    }

    /**
     * Check if user has PIN set
     */
    public function hasPin(): bool
    {
        return !is_null($this->pin);
    }

    /**
     * Verify PIN
     */
    public function verifyPin(string $pin): bool
    {
        return $this->hasPin() && $this->pin === $pin;
    }
}
