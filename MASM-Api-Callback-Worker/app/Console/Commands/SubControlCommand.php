<?php

namespace App\Console\Commands;

use App\Jobs\SubExpireJob;
use App\Lib\Worker;
use App\Models\Device;
use App\Models\Endpoints;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SubControlCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SubControlCommand:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subs Status Control and update';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {
        if(Cache::has('Subs')){
            $Subs = Cache::get('Subs');
            foreach ($Subs as $key=>$Sub){
                $uid = $Sub['uid'];
                $receipt = $Sub['receipt'];
                Worker::purchaseSet($uid,$receipt);
            }
        }
        $dateTime = Carbon::now('-06:00');
        $result = Subscription::where('expire_date', '<', $dateTime)->get();
        Cache::put('Subs',$result);
        foreach ($result as $key => $Subscription) {
            $uid = $Subscription->uid;
            $receipt = $Subscription->receipt;
            Worker::purchaseSet($uid,$receipt);
        }
    }
}
