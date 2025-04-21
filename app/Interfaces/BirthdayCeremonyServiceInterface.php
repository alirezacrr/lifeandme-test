<?php

namespace App\Interfaces;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Morilog\Jalali\Jalalian;

interface BirthdayCeremonyServiceInterface
{
    public function getUsersWithBirthdaysInCurrentMonth(): Collection;
    
    public function getAvailableDaysForCeremony(Collection $usersWithBirthdays): array;
    
    public function getPersianMonthNameAdjective(int $monthNumber): string;
    
    public function createBirthdayCeremonyNotification(
        Collection $usersWithBirthdays,
        Carbon $selectedDate,
        Jalalian $jalaliDate
    ): Notification;
    
    public function selectRandomDayForCeremony(): array|null;
}
