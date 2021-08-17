<?php

namespace App\Http\Controllers;

use App\Lib\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function report(Request $request){
        $validator = Validator::make($request->all(), [
            'day' => 'required|string',
            'appId' => 'required|string',
            'operating_system' => 'required|string'
        ]);

        if ( $validator->fails() ) {
            Log::error('Request validation failed.', [
                'request' => $request->all(),
                'errors' => $validator->errors()
            ]);

            return Response::json($validator->errors());
        }

        $day = $request->day;

        $appId = $request->appId;

        $operating_system = $request->operating_system;

        $report = Report::reportGet($day,$appId,$operating_system);

        return Response::json($report);

    }
}
