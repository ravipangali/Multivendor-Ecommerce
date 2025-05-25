<?php

namespace App\Policies;

use App\Models\SaasPaymentMethod;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SaasPaymentMethodPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        // Admin can do anything
        if ($user->role === 'admin') {
            return true;
        }

        return null; // Fall through to the policy methods
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Any authenticated user can view their own payment methods
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SaasPaymentMethod $saasPaymentMethod): bool
    {
        // User can view their own payment methods
        // Admin can view any payment method (handled by before method)
        return $user->id === $saasPaymentMethod->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Any authenticated user can create payment methods
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SaasPaymentMethod $saasPaymentMethod): bool
    {
        // User can update their own payment methods
        // Admin can update any payment method (handled by before method)
        return $user->id === $saasPaymentMethod->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SaasPaymentMethod $saasPaymentMethod): bool
    {
        // User can delete their own payment methods
        // Admin can delete any payment method (handled by before method)
        return $user->id === $saasPaymentMethod->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SaasPaymentMethod $saasPaymentMethod): bool
    {
        // User can restore their own payment methods
        // Admin can restore any payment method (handled by before method)
        return $user->id === $saasPaymentMethod->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SaasPaymentMethod $saasPaymentMethod): bool
    {
        // User can force delete their own payment methods
        // Admin can force delete any payment method (handled by before method)
        return $user->id === $saasPaymentMethod->user_id;
    }
}
