<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Token;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function tokenControl($tokenRaw){
            $clientToken = $tokenRaw;
            while($clientToken){
                $basicToken = Hash::make($tokenRaw."".Str::random(100));
                $clientToken=Token::where('token',$basicToken)->first();
            }
            return $basicToken;

    }

    public function register(Request $request){
            if($request->uid && $request->appId) {
                $tokenRaw = $request->uid."".$request->appId;
                $basicToken = $this->tokenControl($tokenRaw);
                Token::where('uid',$request->uid)->delete();
                Token::create([
                        "uid" => $request->uid,
                        "token" => $basicToken
                ]);
            }else{
                return true;
            }
            try {
                Device::create([
                    "uid" => $request->uid,
                    "appId" => $request->appId,
                    "language" => $request->language,
                    "operating_system" => $request->operating_system
                ]);
                return ["token"=>$basicToken];
            }catch (\Exception $e) {
                return ["token"=>$basicToken];
            }
    }

}
