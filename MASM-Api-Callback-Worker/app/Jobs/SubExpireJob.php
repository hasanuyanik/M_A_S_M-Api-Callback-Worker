<?php

namespace App\Jobs;

use App\Models\Device;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SubExpireJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $uid, $receipt;

    public $tries=10;

    public function __construct($uid,$receipt)
    {
        $this->uid = $uid;

        $this->receipt = $receipt;
    }


    public function handle()
    {
        $uid = $this->uid;

        $receipt = $this->receipt;

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

        if($apiResponse->error == "429 too many requests error"){

            SubExpireJob::dispatch($uid,$receipt);

            return true;

        }

        ($uid != 0) ? Subscription::updateOrDelete(
            [
                'receipt' => $receipt,
                'uid' => $uid,
                'status' => $apiResponse['status'],
                'expire_date' => $apiResponse['expire_date']
            ],
            ['uid' => $uid]
        ) : $apiResponse = false;

        return $apiResponse;
    }
}
