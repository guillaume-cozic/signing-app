<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        if(!env('IS_DEMO')){
            return;
        }
        $this->call('migrate:fresh', ['--force' => true, '--seeder' => 'DemoSeeder']);
    }
}
