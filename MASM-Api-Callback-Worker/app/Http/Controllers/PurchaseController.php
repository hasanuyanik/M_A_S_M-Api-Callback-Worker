<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Subscription;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    public function purchase(Request $request){
        $validator = Validator::make($request->all(), [
            'client_token' => 'required|string',
            'receipt' => 'required|string'
        ]);

        if ( $validator->fails() ) {
            Log::error('Request validation failed.', [
                'request' => $request->all(),
                'errors' => $validator->errors()
            ]);

            return Response::json($validator->errors());
        }
            $os=($deviceTable=Device::where('token',$request->client_token)->first()) ? $deviceTable->operating_system : "android";
            $endPoint = ($os == "ios") ? "ios" : "google";
            $response = Http::post("https://hasanuyanik.com/mock/ExamLaravel/public/api/".$endPoint, [
                'receipt' => $request->receipt,
            ])->json();
            if($deviceTable) {
                (Subscription::updateOrCreate(
                    [
                        'receipt' => $request->receipt,
                        'uid' => $deviceTable->uid,
                        'status' => $response['status'],
                        'expire_date' => $response['expire_date']
                    ],
                    ['uid' => $deviceTable->uid]
                )) ? Log::info("Subscription Updated/Created") : $response = ["Satın alma işlemi yapılamadı"];
            }
            return Response::json($response);
    }

}
