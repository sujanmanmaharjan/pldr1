<?php

namespace App\Policies;

use App\Models\BadLoan;
use App\Models\User;

class BadLoanPolicy
{
    /**
     * Determine whether the user can view any bad loans.
     */
    public function viewAny(User $user): bool
    {
        return true; // Filtered at query level via scopeForUser
    }

    /**
     * Determine whether the user can view the bad loan.
     */
    public function view(User $user, BadLoan $badLoan): bool
    {
        return $user->isCentral() || $user->branch_id === $badLoan->branch_id;
    }

    /**
     * Determine whether the user can create bad loans.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the bad loan.
     */
    public function update(User $user, BadLoan $badLoan): bool
    {
        return $user->isCentral() || $user->branch_id === $badLoan->branch_id;
    }

    /**
     * Determine whether the user can delete the bad loan.
     */
    public function delete(User $user, BadLoan $badLoan): bool
    {
        return $user->isCentral() || $user->branch_id === $badLoan->branch_id;
    }

    /**
     * Determine whether the user can manage recovery status modules.
     * Central Recovery Office staff only!
     */
    public function manageRecovery(User $user, BadLoan $badLoan): bool
    {
        return $user->isCentral();
    }
}

