<?php

namespace App\Lib;

use App\Jobs\CallbackJob;
use App\Models\Endpoints;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Callback extends Facade {

    static function callbackSend(String $appId, String $deviceId, String $event){
        $appId = $appId; $deviceId = $deviceId; $event = $event;

        $MockUrl = "https://hasanuyanik.com/mock/ExamLaravel/public/api/";

        $callbackEndPoint = EndPoints::where('name','callback')->first('endpoint')->endpoint;

        $callbackResponse = Http::post($MockUrl.$callbackEndPoint, [
            'appID' => $appId,
            'deviceID' => $deviceId,
            'event' => $event
        ])->status();

        if($callbackResponse != 200 && $callbackResponse != 201){
            CallbackJob::dispatch($appId,$deviceId,$event);
            Log::info("Callback işlemi kuyruğa aktarıldı. Event:".$event." - AppId:".$appId." - DeviceId".$deviceId);
            return;
        }
        Log::info("Callback işlemi başarılı. Event:".$event." - AppId:".$appId." - DeviceId".$deviceId);
    }

}
