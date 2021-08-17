<?php

namespace App\Lib;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Response;
use Psy\Util\Json;

class Report extends Facade
{

    static function reportSet(String $appId, String $operating_system, String $event)
    {
        $dateTime = Carbon::now('-06:00');
        $today = $dateTime->format('Y-m-d');

        $todayData = \App\Models\Report::where(
            [
                'day'=>$today,
                'appId' => $appId,
                'operating_system' => $operating_system
            ]
        )->first();

        $started_piece = ($todayData->started_piece)+($event == "Started") ? 1 : 0;
        $renewed_piece = ($todayData->renewed_piece)+($event == "Renewed") ? 1 : 0;
        $canceled_piece = ($todayData->canceled_piece)+($event == "Canceled") ? 1 : 0;

        \App\Models\Report::updateOrCreate(
            [
                'day'=>$today,
                'appId' => $appId,
                'operating_system' => $operating_system
            ],
            [
                'day' => $today,
                'appId' => $appId,
                'operating_system' => $operating_system,
                'started_piece' => $started_piece,
                'renewed_piece' => $renewed_piece,
                'canceled_piece' => $canceled_piece
            ]
        );
    }

    static function reportGet($day, String $appId, String $operating_system):Json{
        $dayData = \App\Models\Report::where(
            [
                'day'=>$day,
                'appId' => $appId,
                'operating_system' => $operating_system
            ]
        )->get();

        return Response::json($dayData);
    }

}
