<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //run the schedule of the projects (only weekdays)
        $schedule->command('app:project:schedule')
            ->weekdays()
            ->twiceDaily(1, 13)
            ->withoutOverlapping()
            ->emailOutputTo(env('CRON_MAIL_ADDRESS'), true)
            ->runInBackground();

        $schedule->command('app:ideas:sendweeklyideas')
            ->weeklyOn(1, '8:00')
            ->withoutOverlapping()
            ->emailOutputTo(env('CRON_MAIL_ADDRESS'), true)
            ->runInBackground();

        //run the roles compete
        $schedule->command('app:roles:compete')
            ->weekdays()
            ->quarterly()
            ->withoutOverlapping()
            ->emailOutputTo(env('CRON_MAIL_ADDRESS'), true)
            ->runInBackground();

        //run the issues totals
        $schedule->command('app:issue:calculatetotals')
            ->weekdays()
            ->twiceDaily(2, 14)
            ->withoutOverlapping()
            ->emailOutputTo(env('CRON_MAIL_ADDRESS'), true)
            ->runInBackground();

        //run the tools totals
        $schedule->command('app:tool:calculatetotals')
            ->weekdays()
            ->twiceDaily(3, 15)
            ->withoutOverlapping()
            ->emailOutputTo(env('CRON_MAIL_ADDRESS'), true)
            ->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
