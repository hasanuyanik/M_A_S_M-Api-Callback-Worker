<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Token;
use Illuminate\Http\Request;

class CheckSubscriptionController extends Controller
{
    public function checkSubscription(Request $request){
        $uid=($tokenTable=Token::where('token',$request->client_token)->first()) ? $tokenTable->uid : 0;
        $status=($purchaseTable=Subscription::where('uid',$uid)->first()) ? $purchaseTable->status : 0;
        return ["status"=>$status];
    }
}
