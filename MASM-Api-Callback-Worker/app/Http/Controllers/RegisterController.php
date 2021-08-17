<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register(Request $request){
            $validator = Validator::make($request->all(), [
                'uid' => 'required|string',
                'appId' => 'required|string',
                'language' => 'required|string',
                'operating_system' => 'required|string'
            ]);

            if ( $validator->fails() ) {
                Log::error('Request validation failed.', [
                    'request' => $request->all(),
                    'errors' => $validator->errors()
                ]);

                return Response::json($validator->errors());
            }

            $uid = $request->uid;

            $appId = $request->appId;

            $language = $request->language;

            $operating_system = $request->operating_system;

            $tokenRaw = $uid."".$appId;

            $token = Hash::make($tokenRaw."".Str::random(10));



                Device::updateOrCreate(
                ['uid' => $uid],
                [
                    "uid" => $uid,
                    "appId" => $appId,
                    "language" => $language,
                    "operating_system" => $operating_system,
                    "token" => $token
                ]
                );

                $response = ["token"=>$token];
                return Response::json($response);

    }

}
