<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Scalar\String_;

class SubscriptionController extends Controller
{
    public function checkSubscription(Request $request){
        $validator = Validator::make($request->all(), [
            'client_token' => 'required|string'
        ]);

        if ( $validator->fails() ) {

            Log::error('Request validation failed.', [
                'request' => $request->all(),
                'errors' => $validator->errors()
            ]);

            return Response::json($validator->errors());
        }

        if(Cache::has('checkSub_Status'.$request->client_token)){

            $status = Cache::get('checkSub_Status'.$request->client_token);

        }else {

            $uid = ($tokenTable = Device::where('token', $request->client_token)->first()) ? $tokenTable->uid : 0;

            $status = ($purchaseTable = Subscription::where('uid', $uid)->first()) ? $purchaseTable->status : 0;

            $cacheName = 'checkSub_Status'.$request->client_token;

            Cache::put($cacheName,$status);
        }
        $response = ["status"=>$status];
        return Response::json($response);
    }

}
