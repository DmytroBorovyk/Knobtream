<?php

namespace App\Console\Commands;

use App\Jobs\AddUserCoins;
use Illuminate\Console\Command;

class DailyCoinAddCommand extends Command
{
    protected $signature = 'app:daily:coin-add';

    public function handle()
    {
        AddUserCoins::dispatch();
    }
}
