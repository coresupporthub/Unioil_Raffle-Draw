<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RaffleEntries;
use App\Models\Event;
use App\Models\RegionalCluster;
use App\Models\RetailStore;
use Illuminate\Support\Arr;

class RaffleController extends Controller
{
    public function getraffleentry(request $request){

        $event = Event::where('event_status', 'Active')->first();
        $retailStores = RetailStore::where('cluster_id', $request->id)->pluck('store_code');
        $raffleEntries = RaffleEntries::where('winner_status', 'false')
        ->whereIn('retail_store_code', $retailStores)
        ->where('event_id', $event->event_id)
        ->get();

        return response()->json($raffleEntries);
    }

    public function raffledraw(request $request){

        $event = Event::where('event_status', 'Active')->first();
        $retailStores = RetailStore::where('cluster_id', $request->id)->pluck('store_code');
        $raffleEntries = RaffleEntries::where('winner_status', 'false')
        ->whereIn('retail_store_code', $retailStores)
        ->where('event_id', $event->event_id)
        ->select('serial_number')
        ->get();

        $shuffledSerialNumbers = $raffleEntries->pluck('serial_number')->toArray();
        shuffle($shuffledSerialNumbers);

        $winnerSerialNumber = $shuffledSerialNumbers[0]; 

        return response()->json([
            'winner_serial_number' => $winnerSerialNumber
        ]);

    }
}
