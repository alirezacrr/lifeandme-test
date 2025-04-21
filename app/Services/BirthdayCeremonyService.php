<?php

namespace App\Services;

use App\Enums\NotificationTypeEnum;
use App\Interfaces\BirthdayCeremonyServiceInterface;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Morilog\Jalali\Jalalian;

class BirthdayCeremonyService implements BirthdayCeremonyServiceInterface
{

    public function getUsersWithBirthdaysInCurrentMonth(): Collection
    {
        $today = Jalalian::now();
        $lastDayOfMonth = $today->getEndDayOfMonth();

        $firstDayOfMonthGregorian = $today->toCarbon()->toDateString();
        $lastDayOfMonthGregorian = $lastDayOfMonth->toCarbon()->toDateString();

        return User::role(['employee', 'admin'])
            ->whereBetween('birthday', [$firstDayOfMonthGregorian, $lastDayOfMonthGregorian])
            ->get();
    }

    public function getAvailableDaysForCeremony(Collection $usersWithBirthdays): array
    {
        $today = Jalalian::now();
        $lastDayOfMonth = $today->getEndDayOfMonth();

        $takenDays = $usersWithBirthdays->map(function ($user) {
            return Carbon::parse($user->birthday)->format('m-d');
        })->toArray();

        $allDays = [];
        $currentDay = $today->toCarbon();
        $lastDay = $lastDayOfMonth->toCarbon();

        while ($currentDay->lte($lastDay)) {
            $allDays[] = $currentDay->format('m-d');
            $currentDay->addDay();
        }

        return array_diff($allDays, $takenDays);
    }

    public function getPersianMonthNameAdjective(int $monthNumber): string
    {
        return match ($monthNumber) {
            1 => 'فروردینی',
            2 => 'اردیبهشتی',
            3 => 'خردادی',
            4 => 'تیرماهی',
            5 => 'مردادی',
            6 => 'شهریوری',
            7 => 'مهرماهی',
            8 => 'آبان‌ماهی',
            9 => 'آذرماهی',
            10 => 'دی‌ماهی',
            11 => 'بهمنی',
            12 => 'اسفندی',
            default => '',
        };
    }

    public function createBirthdayCeremonyNotification(Collection $usersWithBirthdays, Carbon $selectedDate, Jalalian $jalaliDate): Notification
    {
        $today = Jalalian::now();
        $userNames = $usersWithBirthdays->map(function ($user) {
            return $user->name . ' ' . $user->family;
        })->implode('، ');

        $monthNameFa = $this->getPersianMonthNameAdjective($today->getMonth());
        $day = $jalaliDate->format('j');

        $title = "تولد همکارای {$monthNameFa} نزدیکه! 🎂";
        $description = "تولد دوستان {$monthNameFa} ما نزدیکه و این بار قراره جشن بگیریم برای {$userNames} عزیز، در روز {$day} ام همین ماه! 🍰🥳 بیاید یه تبریک حسابی بگیم و خوش بگذرونیم!";

        $notification = Notification::create([
            'title' => $title,
            'description' => $description,
            'type' => NotificationTypeEnum::BIRTHDAY,
            'expire_at' => $selectedDate->toDateString(),
            'meta' => json_encode([
                'date' => $selectedDate->toDateString()
            ]),
        ]);

        $userIds = User::role(['employee', 'admin'])
            ->whereNotIn('id', $usersWithBirthdays->pluck('id'))
            ->pluck('id')
            ->toArray();

        $notification->users()->attach($userIds);

        return $notification;
    }


    public function selectRandomDayForCeremony(): array|null
    {
        $usersWithBirthdays = $this->getUsersWithBirthdaysInCurrentMonth();

        if ($usersWithBirthdays->isEmpty()) {
            return null;
        }

        $availableDays = $this->getAvailableDaysForCeremony($usersWithBirthdays);

        if (count($availableDays) > 0) {
            $randomIndex = array_rand($availableDays);
            $availableDay = $availableDays[$randomIndex];

            $selectedDate = Carbon::createFromFormat('m-d', $availableDay)->setYear(Carbon::now()->year);
            $jalaliDate = Jalalian::fromCarbon($selectedDate);

            $notification = $this->createBirthdayCeremonyNotification($usersWithBirthdays, $selectedDate, $jalaliDate);

            return [
                'users' => $usersWithBirthdays,
                'selected_date' => $selectedDate,
                'jalali_date' => $jalaliDate,
                'notification' => $notification,
            ];
        }

        return null;
    }
}
