<?php

namespace App\Jobs;

use App\Lib\Callback;
use App\Lib\Worker;
use App\Models\Device;
use App\Models\Endpoints;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SubExpireJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $uid, $receipt;

    public $tries=3;

    public function __construct($uid,$receipt)
    {
        $this->uid = $uid;

        $this->receipt = $receipt;
    }


    public function handle()
    {
        $uid = $this->uid;

        $receipt = $this->receipt;

        Worker::purchaseSet($uid,$receipt);
    }
}
