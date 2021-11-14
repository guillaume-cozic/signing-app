<?php

namespace App\Console;

use App\Console\Commands\ResetDemo;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        //
    ];

    protected function schedule(Schedule $schedule)
    {
        if(env('IS_DEMO')) {
            $schedule->command(ResetDemo::class)->dailyAt('23:00');
        }else{
            $schedule->command('backup:run')->dailyAt('01:00');
            $schedule->command('backup:clean')->dailyAt('00:30');
        }
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
