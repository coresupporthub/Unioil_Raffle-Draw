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
        $activeEvent = Event::where('event_status', 'active')->first();
    
        if (!$activeEvent) {
            return response()->json([
                'success' => false,
                'message' => 'No active event found.',
                'eventData' => null,
            ]);
        }
    
        $customers = Customers::where('event_id', $eventId)->get();
        $products = ProductList::whereIn('product_id', $customers->pluck('product_purchased'))->get();
    
        $semiSynthetic = $products->where('entries', 1)->count();
        $fullySynthetic = $products->where('entries', 2)->count();
    
        if ($customers->isEmpty() || $products->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No data for selected event.',
                'eventData' => $activeEvent,
                'semiSynthetic' => 0,
                'fullySynthetic' => 0,
            ]);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Event data fetched successfully.',
            'eventData' => $activeEvent,
            'semiSynthetic' => $semiSynthetic,
            'fullySynthetic' => $fullySynthetic,
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
