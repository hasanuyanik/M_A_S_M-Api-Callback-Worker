<?php

namespace App\Jobs;

use App\Lib\Callback;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CallbackJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $appId, $deviceId, $event;

    public $tries=10;

    public function __construct($appId, $deviceId, $event)
    {
        $this->appId = $appId;

        $this->deviceId = $deviceId;

        $this->event = $event;
    }


    public function handle()
    {
        $appId = $this->appId; $deviceId = $this->deviceId; $event = $this->event;

        Callback::callbackSend($appId,$deviceId,$event);

    }
}
