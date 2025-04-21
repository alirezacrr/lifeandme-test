<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Morilog\Jalali\Jalalian;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentJalaliDate = Jalalian::now();
        $currentJalaliMonth = $currentJalaliDate->getMonth();
        $daysInJalaliMonth = $currentJalaliDate->getMonthDays();

        for ($i = 0; $i < 3; $i++) {
            $randomDay = rand(1, $daysInJalaliMonth);
            $jalaliDate = new Jalalian(
                $currentJalaliDate->getYear(),
                $currentJalaliMonth,
                $randomDay
            );

            $gregorianDate = $jalaliDate->toCarbon();

            User::factory()->employee()->create([
                'birthday' => $gregorianDate
            ]);
        }
    }
}
