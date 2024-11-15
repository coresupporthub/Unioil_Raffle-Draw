<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RaffleEntries;
use App\Models\Event;
use App\Models\RegionalCluster;
use App\Models\RetailStore;
use App\Models\Customers;
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

        $check = $this->validateclusterwinner($request->id);
        
        if($check){
            $cluster_name = RegionalCluster::where('cluster_id', $request->id)->first()->cluster_name;
            return response()->json([
               'success'=>false,
               'message'=> $cluster_name.' already have a winner',
            ]);
        }

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

        $winnerRaffleEntry = RaffleEntries::where('serial_number', $winnerSerialNumber)->first();
        $winnerRaffleEntry->winner_status = 'true';
        $winnerRaffleEntry->save();

        return response()->json([
            'success' => true,
            'winner_serial_number' => $winnerSerialNumber
        ]);

    }

    public function getallwinner(){

        $event = Event::where('event_status', 'Active')->first();
        $raffleEntries = RaffleEntries::where('winner_status', 'true')
        ->where('event_id', $event->event_id)
        ->get();
        $data = [];
        foreach($raffleEntries as $entry){
            $retailStores = RetailStore::where('store_code',$entry->retail_store_code)->first();
            $cluster = RegionalCluster::where('cluster_id',$retailStores->cluster_id)->first()->cluster_name;
            $customer = Customers::where('customer_id',$entry->customer_id)->first()->full_name;
            $data[] = [
                'serial_number' => $entry->serial_number,
                'customer_name' => $customer,
                'cluster'=>$cluster
            ];
        }
        return response()->json($data);
    }

    public function validateclusterwinner($id){

        $event = Event::where('event_status', 'Active')->first();
        $retailStores = RetailStore::where('cluster_id', $id)->pluck('store_code');
        $raffleEntries = RaffleEntries::where('winner_status', 'true')
        ->whereIn('retail_store_code', $retailStores)
        ->where('event_id', $event->event_id)
        ->first();
        
        if($raffleEntries){
            return true;
        }else{
            return false;
        }

    }

    public function getallentry(){

        $event = Event::where('event_status', 'Active')->first();
        $raffleEntries = RaffleEntries::where('event_id', $event->event_id)->get();
        $data = [];
        foreach($raffleEntries as $entry){
            $retailStores = RetailStore::where('store_code',$entry->retail_store_code)->first();
            $cluster = RegionalCluster::where('cluster_id',$retailStores->cluster_id)->first()->cluster_name;
            $customer = Customers::where('customer_id',$entry->customer_id)->first();
            $data[] = [
                'cluster' => $cluster,
                'retail_name' => $retailStores->store_name,
                'serial_number' => $entry->serial_number,
                'product_type'=> $customer->product_purchased,
                'customer_name' => $customer->full_name,
                'customer_email'=> $customer->email,
                'customer_phone' => $customer->mobile_number,
                ];
                }
                return response()->json($data);     
    }

}
