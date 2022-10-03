<?php

namespace App\Jobs;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class AddUserCoins implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

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
