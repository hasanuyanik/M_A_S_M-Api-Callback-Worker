<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

$user = User::find(1);

class token extends Controller
{
    public function index(){
        $user = User::find(1);
        $token = $user->createToken('Token Name')->accessToken;

        return response(['access_token'=>$token]);
    }
}
