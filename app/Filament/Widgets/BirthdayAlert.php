<?php

namespace App\Filament\Widgets;

use App\Interfaces\BirthdayNotificationServiceInterface;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BirthdayAlert extends Widget
{
    protected static string $view = 'filament.widgets.birthday-alert';
    protected int | string | array $columnSpan = 'full';


    protected  $birthdayNotificationService;

    public function mount(BirthdayNotificationServiceInterface $birthdayNotificationService)
    {
        $this->birthdayNotificationService = $birthdayNotificationService;
    }

    public function getViewData(): array
    {
        $user = Auth::user();

        $birthdayNotification = $this->birthdayNotificationService->getBirthdayCeremonyNotification($user);
        $isTodayBirthday = $user->birthday?->isBirthday(Carbon::today());

        return [
            'notification' => $birthdayNotification,
            'isTodayBirthday' => $isTodayBirthday,
        ];
    }

}
