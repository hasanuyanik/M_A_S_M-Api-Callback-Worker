<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Query\Grammars\MySqlGrammar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MockController extends Controller
{
    public function receiptControl(Request $request){
        $lastChar = substr($request->receipt, -1);
        $status = false;
        $dateTime = Carbon::now('-06:00');
        $expire_date = $dateTime->format('Y-m-d H:i:s');
        if (is_numeric($lastChar) && $lastChar % 2 != 0){ $status = true; }
        $response = [
            "status" => $status,
            "expire-date" => $expire_date
        ];
       return $response;
    }

}
