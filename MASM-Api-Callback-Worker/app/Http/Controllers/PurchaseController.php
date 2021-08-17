<?php

namespace App\Http\Controllers;

use App\Lib\Callback;
use App\Lib\Report;
use App\Models\Device;
use App\Models\Endpoints;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    public function purchase(Request $request){

        $MockUrl = "https://hasanuyanik.com/mock/ExamLaravel/public/api/";

        $googleEndPoint = EndPoints::where('name','google')->first('endpoint')->endpoint;

        $iosEndPoint = EndPoints::where('name','ios')->first('endpoint')->endpoint;


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
        $receipt = $request->receipt;

            $os=($deviceTable=Device::where('token',$request->client_token)->first()) ? $deviceTable->operating_system : "android";
            $endPoint = ($os == "ios") ? $iosEndPoint : $googleEndPoint;

            $apiResponse = Http::post($MockUrl.$endPoint, [
                'receipt' => $receipt,
            ]);

            $response = [
                'status' => false,
                'message' => 'Yeni Satın alma işlemi yapılamadı'
            ];

            $appId = $deviceTable->appId;

            $uid = $deviceTable->uid;

            if($apiResponse->status() == 429){

                return Response::json($response);

            }

            $status = $apiResponse->json()['status'];

            $expire_date = $apiResponse->json()['expire_date'];

            $event = "Started";


        if($deviceTable) {


                $purchase = Subscription::updateOrCreate(
                    [
                        'receipt' => $receipt,
                        'uid' => $uid
                    ],
                    [
                        'receipt' => $receipt,
                        'uid' => $uid,
                        'status' => $status,
                        'expire_date' => $expire_date
                    ]
                );

                $response= $apiResponse->json();

                Report::reportSet($appId,$os,"Started");

                Callback::callbackSend($appId,$uid,$event);
        }

            return Response::json($response);
    }

}
