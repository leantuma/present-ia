<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Plan;
use App\Models\Subscription;
use Carbon\Carbon;

class SubscriptionService
{
    /**
     * Crea una suscripción trial para una empresa
     *
     * @param Company $company
     * @param Plan $plan
     * @param int $trialDays
     * @return Subscription
     */
    public function createTrial(Company $company, Plan $plan, int $trialDays = 14): Subscription
    {
        return Subscription::create([
            'company_id' => $company->id,
            'plan_id' => $plan->id,
            'status' => 'trial',
            'trial_ends_at' => Carbon::now()->addDays($trialDays),
            'starts_at' => Carbon::now(),
            'ends_at' => null,
        ]);
    }

    /**
     * Activa una suscripción
     *
     * @param Subscription $subscription
     * @param string|null $stripeSubscriptionId
     * @param string|null $stripeCustomerId
     * @return Subscription
     */
    public function activate(Subscription $subscription, ?string $stripeSubscriptionId = null, ?string $stripeCustomerId = null): Subscription
    {
        $subscription->update([
            'status' => 'active',
            'starts_at' => Carbon::now(),
            'ends_at' => null, // O calcular según el plan
            'stripe_subscription_id' => $stripeSubscriptionId,
            'stripe_customer_id' => $stripeCustomerId,
        ]);

        return $subscription->fresh();
    }

    /**
     * Cancela una suscripción
     *
     * @param Subscription $subscription
     * @return Subscription
     */
    public function cancel(Subscription $subscription): Subscription
    {
        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => Carbon::now(),
        ]);

        return $subscription->fresh();
    }

    /**
     * Suspende una suscripción
     *
     * @param Subscription $subscription
     * @return Subscription
     */
    public function suspend(Subscription $subscription): Subscription
    {
        $subscription->update([
            'status' => 'suspended',
        ]);

        return $subscription->fresh();
    }

    /**
     * Verifica si una empresa puede agregar más empleados
     *
     * @param Company $company
     * @return bool
     */
    public function canAddEmployees(Company $company): bool
    {
        $subscription = $company->subscription;
        
        if (!$subscription || !$subscription->isActive()) {
            return false;
        }

        $currentEmployeeCount = $company->users()->where('role', 'employee')->count();
        
        return $subscription->canAddEmployees($currentEmployeeCount);
    }

    /**
     * Obtiene el límite de empleados para una empresa
     *
     * @param Company $company
     * @return int
     */
    public function getEmployeeLimit(Company $company): int
    {
        $subscription = $company->subscription;
        
        if (!$subscription) {
            return 0;
        }

        return $subscription->plan->max_employees;
    }

    /**
     * Actualiza el plan de una suscripción
     *
     * @param Subscription $subscription
     * @param Plan $newPlan
     * @return Subscription
     */
    public function changePlan(Subscription $subscription, Plan $newPlan): Subscription
    {
        $subscription->update([
            'plan_id' => $newPlan->id,
        ]);

        return $subscription->fresh();
    }
}

