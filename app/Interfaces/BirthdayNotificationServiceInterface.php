<?php

namespace App\Interfaces;

use App\Models\User;

interface BirthdayNotificationServiceInterface
{
    public function getBirthdayCeremonyNotification(User $user);
}
