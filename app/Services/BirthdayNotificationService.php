<?php

namespace App\Services;

use App\Enums\NotificationTypeEnum;
use App\Interfaces\BirthdayNotificationServiceInterface;
use App\Models\User;
use Carbon\Carbon;

class BirthdayNotificationService implements BirthdayNotificationServiceInterface
{
    public function getBirthdayCeremonyNotification(User $user)
    {
        return $user->notifications()
            ->where('type', NotificationTypeEnum::BIRTHDAY)
            ->where(function ($query) {
                $query->whereNull('expire_at')
                    ->orWhere('expire_at', '>=', Carbon::today()->toDateString());
            })
            ->whereNotIn('notifications.id', function ($query) use ($user) {
                $query->select('notification_id')
                    ->from('notification_user_dismissed')
                    ->where('user_id', $user->id);
            })
            ->latest()
            ->first();
    }
}
