<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'plan_id',
        'status',
        'trial_ends_at',
        'starts_at',
        'ends_at',
        'cancelled_at',
        'stripe_subscription_id',
        'stripe_customer_id',
        'metadata',
    ];

    protected $casts = [
        'trial_ends_at' => 'date',
        'starts_at' => 'date',
        'ends_at' => 'date',
        'cancelled_at' => 'date',
        'metadata' => 'array',
    ];

    /**
     * Get the company this subscription belongs to
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the plan for this subscription
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && 
               ($this->ends_at === null || $this->ends_at->isFuture());
    }

    /**
     * Check if subscription is on trial
     */
    public function isOnTrial(): bool
    {
        return $this->status === 'trial' && 
               $this->trial_ends_at && 
               $this->trial_ends_at->isFuture();
    }

    /**
     * Check if company can add more employees
     */
    public function canAddEmployees(int $currentCount): bool
    {
        return $this->plan->allowsEmployees($currentCount + 1);
    }
}
