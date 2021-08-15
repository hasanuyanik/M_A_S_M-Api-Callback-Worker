<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CheckSubscriptionController extends Controller
{
    public function checkSubscription(Request $request){
        $uid=($tokenTable=DB::table('client_token')->where('token',$request->client_token)->first()) ? $tokenTable->uid : 0;
        $status=($purchaseTable=DB::table('purchase')->where('uid',$uid)->first()) ? $purchaseTable->status : 0;
        return ["status"=>$status];
    }
}
