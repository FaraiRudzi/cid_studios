<?php

namespace App\Policies;

use App\Models\CaseModel;
use App\Models\User;
use App\Models\Photographer;

class CasePolicy
{
    /**
     * Rule: Can an Admin reassign a case's photographer?
     * Only an admin can perform this action.
     */
    public function reassign(User $user, CaseModel $case)
    {
        // This assumes your User model has an 'isAdmin' method or attribute.
        // We will add this in the next step.
        return $user->isAdmin();
    }

    /**
     * Rule: Can a Photographer update a case's details?
     * Only the photographer assigned to the case can do this.
     */
    public function photographerUpdate(Photographer $photographer, CaseModel $case)
    {
        // Check if the logged-in photographer's ID matches the case's photographer_id.
        return $photographer->id === $case->photographer_id;
    }

    // You can add other rules here later, like who can delete a case.
    public function delete(User $user, CaseModel $case)
    {
        return $user->isAdmin();
    }
}