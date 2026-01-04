<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Leave extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'user_id',
        'type',
        'start_date',
        'end_date',
        'status',
        'reason',
        'requested_by',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'status' => 'string',
    ];

    /**
     * Get the company this leave belongs to
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user (employee) this leave is for
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who requested this leave
     */
    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the user who approved/rejected this leave
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if leave is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if leave is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if leave is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Approve the leave
     */
    public function approve(User $approver, string $reason = null): void
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);
    }

    /**
     * Reject the leave
     */
    public function reject(User $rejector, string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $rejector->id,
            'approved_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Calculate the number of days for this leave
     */
    public function getDaysCount(): int
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);
        
        // Add 1 to include both start and end dates
        return $start->diffInDays($end) + 1;
    }

    /**
     * Get the type label in Spanish
     */
    public function getTypeLabel(): string
    {
        return match($this->type) {
            'vacation' => 'Vacaciones',
            'sick' => 'Enfermedad',
            'maternity' => 'Maternidad',
            'other' => 'Otras',
            default => $this->type,
        };
    }

    /**
     * Get the status label in Spanish
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Pendiente',
            'approved' => 'Aprobada',
            'rejected' => 'Rechazada',
            default => $this->status,
        };
    }
}
