<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Company::class => \App\Policies\CompanyPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Leave::class => \App\Policies\LeavePolicy::class,
        \App\Models\Attendance::class => \App\Policies\AttendancePolicy::class,
        \App\Models\Schedule::class => \App\Policies\SchedulePolicy::class,
        \App\Models\Alert::class => \App\Policies\AlertPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
