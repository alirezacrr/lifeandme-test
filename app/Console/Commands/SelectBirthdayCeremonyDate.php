<?php

namespace App\Console\Commands;

use App\Interfaces\BirthdayCeremonyServiceInterface;
use Illuminate\Console\Command;

class SelectBirthdayCeremonyDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthday:date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Select a random date for birthday ceremony';

    protected BirthdayCeremonyServiceInterface $birthdayCeremonyService;

    /**
     * Create a new command instance.
     */
    public function __construct(BirthdayCeremonyServiceInterface $birthdayCeremonyService)
    {
        parent::__construct();
        $this->birthdayCeremonyService = $birthdayCeremonyService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $result = $this->birthdayCeremonyService->selectRandomDayForCeremony();

        if ($result === null) {
            $this->info("Tavalodi To In Mah Nadarimُ"); // ya hameye roza tavalode ye nafari hast :D
            return;
        }

        $this->info("Date Jalali : " . $result['jalali_date']->format('Y-m-d'));
        $this->info("Date Gregorian: " . $result['selected_date']->format('Y-m-d'));
    }
}
