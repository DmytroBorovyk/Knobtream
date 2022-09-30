<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Console\Command;

class DailyCoinAddCommand extends Command
{
    protected $signature = 'app:daily:coin-add';

    public function handle()
    {
        $users = User::get();
        $coins_to_add = Setting::where('setting', 'coins')->first()->value;

        foreach ($users as $user) {
            $user->balance += $coins_to_add;
            if ($user->balance > 5) {
                $user->balance = 5;
            }
            $user->save();
        }
    }
}
