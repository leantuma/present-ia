<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_id',
        'employee_id',
        'department',
        'position',
        'hire_date',
        'metadata',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'metadata' => 'array',
    ];

    /**
     * Get the user this profile belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company this profile belongs to
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
