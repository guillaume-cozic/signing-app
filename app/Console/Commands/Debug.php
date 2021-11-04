<?php

namespace App\Console\Commands;

use App\Models\User;
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
        $user = User::query()->where('email', 'guillaume.cozic@gmail.com')->first();
        $user->assignRole('RTQ');

        //$c = new Client(['host' => env('REDIS_HOST'), 'port' => env('REDIS_PORT')]);
        //dd($c->keys('*'));
    }
}
