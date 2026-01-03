<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'max_employees',
        'price_monthly',
        'price_yearly',
        'features',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'max_employees' => 'integer',
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get all subscriptions for this plan
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Check if plan allows a certain number of employees
     */
    public function allowsEmployees(int $count): bool
    {
        return $count <= $this->max_employees;
    }

    /**
     * Check if plan has a specific feature
     */
    public function hasFeature(string $feature): bool
    {
        return in_array($feature, $this->features ?? []);
    }
}
