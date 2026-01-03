<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'user_id',
        'device_id',
        'name',
        'platform',
        'os_version',
        'app_version',
        'push_token',
        'last_seen_at',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Get the company this device belongs to
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user this device belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Update last seen timestamp
     */
    public function updateLastSeen(): void
    {
        $this->update(['last_seen_at' => now()]);
    }
}
