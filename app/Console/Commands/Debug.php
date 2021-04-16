<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Predis\Client;

class Debug extends Command
{

    protected $signature = 'debug:run';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $c = new Client(['host' => env('REDIS_HOST'), 'port' => env('REDIS_PORT')]);
        dd($c->keys('*'));
    }
}
