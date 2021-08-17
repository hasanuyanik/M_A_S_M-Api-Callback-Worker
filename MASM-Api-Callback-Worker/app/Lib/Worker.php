<?php

namespace App\Lib;

use App\Jobs\SubExpireJob;
use App\Models\Device;
use App\Models\Endpoints;
use App\Models\Subscription;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Worker extends Facade {

    static function purchaseSet($uid,$receipt){
        $MockUrl = "https://hasanuyanik.com/mock/ExamLaravel/public/api/";

        $googleEndPoint = EndPoints::where('name','google')->first('endpoint')->endpoint;

        $iosEndPoint = EndPoints::where('name','ios')->first('endpoint')->endpoint;

        if(Cache::has('DeviceUid'.$uid)) {

            $deviceTable = Cache::get('DeviceUid' . $uid);

            $os = $deviceTable['operating_system'];

        }else{

            $os = ($deviceTable = Device::where('uid', $uid)->first()) ? $deviceTable->operating_system : "android";

        }

        $endPoint = ($os == "ios") ? $iosEndPoint : $googleEndPoint;

        $apiResponse = Http::post($MockUrl.$endPoint, [
            'receipt' => $receipt,
        ]);

        if($apiResponse->status() == 429){

            SubExpireJob::dispatch($uid,$receipt);

            return;

        }
        $appId = $deviceTable->appId;

        $status = $apiResponse->json()['status'];

        $expire_date = $apiResponse->json()['expire_date'];

        if($status == 1) {
            Subscription::where(['uid'=> $uid,'receipt'=> $receipt])->update([
                    'receipt' => $receipt,
                    'uid' => $uid,
                    'status' => $status,
                    'expire_date' => $expire_date
                ]);

            Report::reportSet($appId,$os,"Renewed");

            Callback::callbackSend($appId,$uid,"Renewed");

            return;
        }
        Subscription::where('uid',$uid)->delete();

        Report::reportSet($appId,$os,"Canceled");

        Callback::callbackSend($appId,$uid,"Canceled");

        Log::info('Queue Sub Control is working');
    }

}
