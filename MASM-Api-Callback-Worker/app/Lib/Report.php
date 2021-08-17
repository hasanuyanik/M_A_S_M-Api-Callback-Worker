<?php

namespace App\Lib;

use Carbon\Carbon;
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

            $currentStartedPiece = 0; $currentRenewedPiece = 0; $currentCanceledPiece = 0;
        if($todayData) {
            $currentStartedPiece = ($todayData->started_piece > 0) ? $todayData->started_piece : 0;
            $currentRenewedPiece = ($todayData->renewed_piece > 0) ? $todayData->renewed_piece : 0;
            $currentCanceledPiece = ($todayData->canceled_piece > 0) ? $todayData->canceled_piece : 0;
        }

        $started_piece = $currentStartedPiece+(($event == "Started") ? 1 : 0);
        $renewed_piece = $currentRenewedPiece+(($event == "Renewed") ? 1 : 0);
        $canceled_piece = $currentCanceledPiece+(($event == "Canceled") ? 1 : 0);

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
