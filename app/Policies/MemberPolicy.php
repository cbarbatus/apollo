<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\User;

class MemberPolicy
{
    /**
     * Determine whether the user can view the list of members.
     */
    public function viewAny(User $user): bool
    {
        // This checks if the user's role has the 'see personal' permission
        // we saw in your database screenshot.
        return $user->can('see personal');
    }

    public function update(User $user, Member $member): bool
    {
        // 1. Higher Authority (Roles OR specific Permissions)
        if ($user->hasRole(['admin', 'senior_druid']) ||
            $user->canAny(['change all', 'change members'])) {
            return true;
        }

        // 2. Ownership (The "Self" Key)
        // Only active members have user records [cite: 2025-12-31]
        return (int)$member->user_id === (int)$user->id;
    }

    public function delete(User $user, Member $member): bool
    {
        return $user->is_admin || $user->hasRole('senior_druid');
    }
}
