<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /**
         * Maintain scholarship status or recurrence.
         */
        $schedule->command('scholarship:maintain')
            ->everyMinute()
            ->appendOutputTo(storage_path('logs/scholarship_maintain.log'))
            ->withoutOverlapping();

        /**
         * Scholarship winner notifications.
         */
        $schedule->command('scholarship:winner:notification')
            ->everyFifteenMinutes()
            ->appendOutputTo(storage_path('logs/scholarship_winner_notifications.log'))
            ->withoutOverlapping();
    }
}
