<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetDemo extends Command
{
    protected $signature = 'demo:reset';

    protected $description = 'Reset demo data';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('Start command Reset demo');
        if(!env('IS_DEMO')){
            return;
        }
        $this->call('migrate:fresh', ['--force' => true, '--seeder' => 'DemoSeeder']);
        Log::info('End command Reset demo');
    }
}
