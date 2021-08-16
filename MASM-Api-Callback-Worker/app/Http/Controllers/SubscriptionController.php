<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

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
        $uid=($tokenTable=Device::where('token',$request->client_token)->first()) ? $tokenTable->uid : 0;
        $status=($purchaseTable=Subscription::where('uid',$uid)->first()) ? $purchaseTable->status : 0;
        $response = ["status"=>$status];
        return Response::json($response);
    }

}
