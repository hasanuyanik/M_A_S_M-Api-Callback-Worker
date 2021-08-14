<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class RegisterController extends Controller
{
    public function register(Request $request){
            try {
                DB::table('device')->insert([
                    "uid" => $request->uid,
                    "appId" => $request->appId,
                    "language" => $request->language,
                    "operating-system" => $request->operatingsystem
                ]);
                return true;
            }catch (\Exception $e) {
                return true;
            }
    }

}
