<?php

namespace App\Console\Commands;

use App\Jobs\SubExpireJob;
use App\Models\Device;
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
    public function SubApiControl(String $uid, String $receipt){

            if(Cache::has('DeviceUid'.$uid)) {

                $deviceTable = Cache::get('DeviceUid' . $uid);

                $os = $deviceTable['operating_system'];

            }else{

                $os = ($deviceTable = Device::where('uid', $uid)->first()) ? $deviceTable->operating_system : "android";

            }

            $endPoint = ($os == "ios") ? "ios" : "google";

            $apiResponse = Http::post("https://hasanuyanik.com/mock/ExamLaravel/public/api/" . $endPoint, [
                'receipt' => $receipt,
            ])->json();

        if(!$apiResponse) {
            Log::info('Command Sub Control Not Working');
            return true;
        }

        Subscription::where('uid',$uid)->update(
            [
                'receipt' => $receipt,
                'uid' => $uid,
                'status' => $apiResponse['status'],
                'expire_date' => $apiResponse['expire_date']
            ]);
        Log::info('Command Sub Control is working');
    }

    public function handle()
    {
        if(Cache::has('Subs')){
            $Subs = Cache::get('Subs');
            foreach ($Subs as $key=>$Sub){
                $uid = $Sub['uid'];
                $receipt = $Sub['receipt'];
                $this->SubApiControl($uid,$receipt);
            }
        }
        $dateTime = Carbon::now('-06:00');
        $result = Subscription::where('expire_date', '<', $dateTime)->get();
        Cache::put('Subs',$result);
        foreach ($result as $key => $Subscription) {
            $uid = $Subscription->uid;
            $receipt = $Subscription->receipt;
            $this->SubApiControl($uid,$receipt);
        }
    }
}
