<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Subscription;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;

class PurchaseController extends Controller
{
    public function purchase(Request $request){
        try {
            $uid=($tokenTable=Token::where('token',$request->client_token)->first()) ? $tokenTable->uid : 0;
            $os=($deviceTable=Device::where('uid',$uid)->first()) ? $deviceTable->operating_system : "android";
            $endPoint = ($os == "ios") ? "ios" : "google";
            $apiResponse = Http::post("https://hasanuyanik.com/mock/ExamLaravel/public/api/".$endPoint, [
                'receipt' => $request->receipt,
            ])->json();
            ($uid != 0) ? Subscription::updateOrInsert([
                'receipt' => $request->receipt,
                'uid' => $uid,
                'status' => $apiResponse['status'],
                'expire_date' => $apiResponse['expire_date']
            ]) : $apiResponse=false;
            return $apiResponse;
        }catch (\Exception $e) {
            return $e;
        }

    }
}
