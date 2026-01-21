<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Announcement;

class AnnouncementPolicy
{
    /**
     * This replaces the 'manage' logic.
     * It covers index, create, and store.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'senior_druid']);
    }

    /**
     * Allows managers to edit or delete specific announcements.
     */
    public function update(User $user, Announcement $announcement): bool
    {
        return $user->hasRole(['admin', 'senior_druid']);
    }

    public function delete(User $user, Announcement $announcement): bool
    {
        return $user->hasRole(['admin', 'senior_druid']);
    }
}
