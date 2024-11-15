<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RaffleEntries;
use App\Models\Event;

class RaffleController extends Controller
{
    public function getraffleentry(){

        $raffleentries = RaffleEntries::where('winner_status',false)->get();
        $data = [];
        foreach ($raffleentries as $entry) {
            $event = Event::where('event_id',$entry->event_id)->where('event_status','Active')->first();
            if($event){
                $data[] = [
                    'data'=>$entry
                ];
            }
        }

        return response()->json(['data' => $data]);

    }
}
