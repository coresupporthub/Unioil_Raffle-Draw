<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customers;
use App\Models\ProductList;
use App\Models\Event;
use Illuminate\Support\Facades\DB;


class AnalyticsController extends Controller
{
    public function getEventData($eventId)
    {
        $activeEvent = Event::where('event_status', 'Active')->first();
    
        if (!$activeEvent) {
            return response()->json([
                'success' => false,
                'message' => 'No active event found.',
                'eventData' => null,
            ]);
        }
    
        $customers = Customers::where('event_id', $eventId)->get();
        $countFull = 0;
        $countSemi = 0;
        foreach($customers as $customer){
            $products = ProductList::where('product_id', $customer->product_purchased)->first();
            if($products->entries == 1){
                $countSemi += 1; 
            }else{
                $countFull += 1; 
            }
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Event data fetched successfully.',
            'eventData' => $activeEvent,
            'semiSynthetic' => $countSemi,
            'fullySynthetic' => $countFull,
        ]);
    }
    

    public function getActiveEvent()
    {
        $activeEvent = Event::where('event_status', 'Active')->first();
    
        if ($activeEvent) {
            return response()->json([
                'success' => true,
                'eventData' => $activeEvent
            ]);
        }
    
        return response()->json([
            'success' => false,
            'message' => 'No active event found'
        ]);
    }
}
