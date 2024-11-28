<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customers;
use App\Models\ProductList;
use App\Models\RetailStore;
use App\Models\Event;
use App\Models\RegionalCluster;
use Illuminate\Http\JsonResponse;

class AnalyticsController extends Controller
{
    public function getEventData(string $eventId): JsonResponse
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
        foreach ($customers as $customer) {
            $products = ProductList::where('product_id', $customer->product_purchased)->first();
            if ($products->entries == 1) {
                $countSemi += 1;
            } else {
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


    public function getActiveEvent(): JsonResponse
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

    public function entryissuance(Request $req, string $filter): JsonResponse
    {
        if ($filter == 'active') {
            $event = Event::where('event_status', 'Active')->first();
        } else {
            $event = Event::where('event_id', $filter)->first();
        }

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found.',
            ]);
        }

        $customers = Customers::where('event_id', $event->event_id)
            ->get()
            ->groupBy(function ($date) {
                return $date->created_at->format('Y-m-d');
            });

        $groupedByDate = [];
        foreach ($customers as $date => $records) {
            $groupedByDate[] = [
                'date' => $date,
                'count' => $records->count(),
            ];
        }

        return response()->json([
            'success' => true,
            'eventData' => $groupedByDate,
        ]);
    }


    public function entriesbyproducttype(Request $req, string $event): JsonResponse
    {
        if ($event == 'active') {
            $eventData = Event::where('event_status', 'Active')->first();
        } else {
            $eventData = Event::where('event_id', $event)->first();
        }

        $customers = Customers::where('event_id', $eventData->event_id)
            ->get()
            ->groupBy(function ($date) {

                return $date->created_at->format('Y-m');
            });

        $groupedByMonth = [];

        foreach ($customers as $month => $records) {
            $f_synthetic = 0;
            $s_synthetic = 0;

            foreach($records as $rec){
                $product = ProductList::where('product_id', $rec['product_purchased'])->first();

                if($product->entries == 1){
                    $s_synthetic++;
                }else{
                    $f_synthetic++;
                }
            }

            $groupedByMonth[] = [
                'month' => $month,
                'count' => $records->count(),
                'fully_synthetic' => $f_synthetic,
                'semi_synthetic' => $s_synthetic
            ];
        }

        return response()->json($groupedByMonth);
    }

    public function getClusterData(string $eventId): JsonResponse
    {
        if ($eventId == 'active') {
            $activeEvent = Event::where('event_status', 'Active')->first();

            if (!$activeEvent) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active event found.',
                ]);
            }

            $eventId = $activeEvent->event_id;
        }

        $clusters = RegionalCluster::all();
        $clusterData = [];

        foreach ($clusters as $cluster) {
            $retails = RetailStore::where('cluster_id', $cluster->cluster_id)->get();

            $totalEntries = 0;
            foreach ($retails as $retail) {
                $customers = Customers::where('event_id', $eventId)
                    ->where('store_id', $retail->store_id)
                    ->get();

                foreach ($customers as $customer) {
                    $product = ProductList::where('product_id', $customer->product_purchased)->first();

                    if ($product) {
                        $totalEntries += $product->entries == 2 ? 2 : 1;
                    }
                }
            }

            $clusterData[] = [
                'cluster' => $cluster->cluster_name,
                'entries' => $totalEntries,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Cluster data fetched successfully.',
            'data' => $clusterData,
        ]);
    }

    public function entriesByProductTypeAndCluster(string $eventId): JsonResponse
    {


        $clusters = RegionalCluster::all();
        $clusterData = [];


        foreach ($clusters as $cluster) {
            $full = 0;
            $semi = 0;
            $stores = RetailStore::where('cluster_id',$cluster->cluster_id)->get();
            $productData = [];
            foreach($stores as $store){
                $customers = Customers::where('store_id',$store->store_id)->where('event_id',$eventId)->get();
                foreach($customers as $customer){
                    $product = ProductList::where('product_id',$customer->product_purchased)->first();
                    if($product->entries == 2){
                        $full += 2;
                    }else{
                        $semi += 1;
                    }

                }

            }
            $productData[]=[
                'Fully Synthetic'=>$full,
                'Semi Synthetic'=>$semi,
            ];
            $clusterData[]=[
                'cluster'=>$cluster->cluster_name,
                'product'=>$productData
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Entries by product type and cluster fetched successfully.',
            'data' => $clusterData,
        ]);
    }

}
