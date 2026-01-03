<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceCorrection extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'attendance_id',
        'requested_by',
        'reviewed_by',
        'type',
        'status',
        'requested_changes',
        'reason',
        'review_notes',
        'reviewed_at',
        'metadata',
    ];

    protected $casts = [
        'requested_changes' => 'array',
        'reviewed_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the company this correction belongs to
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the attendance this correction is for
     */
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    /**
     * Get the user who requested this correction
     */
    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the user who reviewed this correction
     */
    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Approve the correction
     */
    public function approve(User $reviewer, ?string $notes = null): void
    {
        $this->update([
            'status' => 'approved',
            'reviewed_by' => $reviewer->id,
            'review_notes' => $notes,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Reject the correction
     */
    public function reject(User $reviewer, ?string $notes = null): void
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_by' => $reviewer->id,
            'review_notes' => $notes,
            'reviewed_at' => now(),
        ]);
    }
}
