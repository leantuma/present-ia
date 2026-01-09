<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'user_id',
        'attendance_id',
        'leave_id',
        'type',
        'title',
        'message',
        'severity',
        'is_read',
        'read_at',
        'metadata',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the company this alert belongs to
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user this alert is for (null = company-wide)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attendance this alert is related to
     */
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    /**
     * Get the leave this alert is related to
     */
    public function leave()
    {
        return $this->belongsTo(Leave::class);
    }

    /**
     * Mark alert as read
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}
