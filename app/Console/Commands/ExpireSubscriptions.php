<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use Carbon\Carbon;

class ExpireSubscriptions extends Command
{
     protected $signature = 'subscriptions:expire';


    public function handle()
    {
        $today = Carbon::today();

        $expired = Subscription::where('status', 'active')
            ->whereDate('end_date', '<', $today)
            ->update(['status' => 'expired']);

        $this->info("Expired {$expired} subscriptions.");
    }
}
