<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RaffleEntries;
use App\Models\Event;
use App\Models\RegionalCluster;
use App\Models\RetailStore;
use App\Models\Customers;
use App\Models\ProductList;
use App\Http\Services\Tools;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class RaffleController extends Controller
{
    public function getraffleentry(Request $request)
    {

        $event = Event::where('event_status', 'Active')->first();
        $retailStores = RetailStore::where('cluster_id', $request->id)->pluck('rto_code');
        $raffleEntries = RaffleEntries::where('winner_status', 'false')->where('winner_record', 'false')
            ->whereIn('retail_store_code', $retailStores)
            ->where('event_id', $event->event_id)
            ->get();

        return response()->json($raffleEntries);
    }

    public function raffledraw(Request $request)
    {

        $check = $this->validateclusterwinner($request->id);

        if ($check) {
            $cluster_name = RegionalCluster::where('cluster_id', $request->id)->first()->cluster_name;
            return response()->json([
                'success' => false,
                'message' => $cluster_name . ' already have a winner',
            ]);
        }

        $event = Event::where('event_status', 'Active')->first();
        $retailStores = RetailStore::where('cluster_id', $request->id)->pluck('rto_code');
        $raffleEntries = RaffleEntries::where('winner_status', 'false')->where('winner_record', 'false')
            ->whereIn('retail_store_code', $retailStores)
            ->where('event_id', $event->event_id)
            ->select('serial_number')
            ->get();

        $shuffledSerialNumbers = $raffleEntries->pluck('serial_number')->toArray();
        shuffle($shuffledSerialNumbers);

        $winnerSerialNumber = $shuffledSerialNumbers[0];

        $winnerRaffleEntry = RaffleEntries::where('serial_number', $winnerSerialNumber)->first();
        $winnerRaffleEntry->winner_status = 'true';
        $winnerRaffleEntry->winner_record = 'true';
        $winnerRaffleEntry->save();

        $customerWinner = Customers::where('customer_id', $winnerRaffleEntry->customer_id)->first();
        $store = RetailStore::where('store_id', $customerWinner->store_id)->join('regional_cluster', 'retail_store.cluster_id', '=', 'regional_cluster.cluster_id')
            ->select('cluster_name')->first();

        $product = ProductList::where('product_id', $customerWinner->product_purchased)->select('product_name')->first();

        $response = [
            'success' => true,
            'winner_serial_number' => $winnerSerialNumber,
            'winner_details' => $customerWinner,
            'cluster_name' => $store,
            'product' => $product
        ];
        Tools::Logger($request, ['Raffle Draw Stated', "Raffle Draw Selected a winner"], $response);
        return response()->json($response);
    }

    public function getallwinner()
    {

        $event = Event::where('event_status', 'Active')->first();
        $raffleEntries = RaffleEntries::where('winner_status', 'true')
            ->where('event_id', $event->event_id)
            ->get();
        $data = [];
        foreach ($raffleEntries as $entry) {
            $retailStores = RetailStore::where('rto_code', $entry->retail_store_code)->first();
            $cluster = RegionalCluster::where('cluster_id', $retailStores->cluster_id)->first()->cluster_name;
            $customer = Customers::where('customer_id', $entry->customer_id)->first();
            $data[] = [
                'event_prize' => $event->event_price,
                'serial_number' => $entry->serial_number,
                'customer_name' => $customer->full_name,
                'customer_email' => $customer->email,
                'customer_number' => $customer->mobile_number,
                'cluster' => $cluster
            ];
        }
        return response()->json($data);
    }

    public function geteventwinner(Request $request)
    {

        $event = Event::where('event_id', $request->event_id)->first();
        $raffleEntries = RaffleEntries::where('winner_status', 'true')
            ->where('event_id', $event->event_id)
            ->get();
        $data = [];
        foreach ($raffleEntries as $entry) {
            $retailStores = RetailStore::where('rto_code', $entry->retail_store_code)->first();
            $cluster = RegionalCluster::where('cluster_id', $retailStores->cluster_id)->first()->cluster_name;
            $customer = Customers::where('customer_id', $entry->customer_id)->first();
            $data[] = [
                'event_prize' => $event->event_prize,
                'serial_number' => $entry->serial_number,
                'customer_name' => $customer->full_name,
                'customer_email' => $customer->email,
                'customer_number' => $customer->mobile_number,
                'cluster' => $cluster
            ];
        }
        return response()->json($data);
    }

    public function geteventunclaim(Request $request)
    {

        $event = Event::where('event_id', $request->event_id)->first();
        $raffleEntries = RaffleEntries::where('winner_status', 'false')
            ->where('winner_record', 'true')
            ->where('event_id', $event->event_id)
            ->get();
        $data = [];
        foreach ($raffleEntries as $entry) {
            $retailStores = RetailStore::where('rto_code', $entry->retail_store_code)->first();
            $cluster = RegionalCluster::where('cluster_id', $retailStores->cluster_id)->first()->cluster_name;
            $customer = Customers::where('customer_id', $entry->customer_id)->first();
            $data[] = [
                'event_prize' => $event->event_prize,
                'serial_number' => $entry->serial_number,
                'customer_name' => $customer->full_name,
                'customer_email' => $customer->email,
                'customer_number' => $customer->mobile_number,
                'cluster' => $cluster
            ];
        }
        return response()->json($data);
    }

    public function validateclusterwinner($id)
    {

        $event = Event::where('event_status', 'Active')->first();
        $retailStores = RetailStore::where('cluster_id', $id)->pluck('rto_code');
        $raffleEntries = RaffleEntries::where('winner_status', 'true')->where('winner_record', 'true')
            ->whereIn('retail_store_code', $retailStores)
            ->where('event_id', $event->event_id)
            ->first();

        if ($raffleEntries) {
            return true;
        } else {
            return false;
        }
    }

    public function getallentry(Request $request)
    {
        $allDataFlag = $request->input('allData', false); // Check if all data is requested

        $start = $request->input('start', 0);
        $length =  $request->input('length', 10);
        $search = $allDataFlag ? null : $request->input('search')['value'];

        $events = !empty($request->event_id)
            ? Event::where('event_id', $request->event_id)->get()
            : Event::all();

        $allData = [];
        foreach ($events as $event) {
            $query = RaffleEntries::where('event_id', $event->event_id);

            if (!empty($request->region)) {
                $retailData = RetailStore::where('cluster_id', $request->region)->pluck('rto_code');
                $query->whereIn('retail_store_code', $retailData);
            }

            $raffleData = $query->get();

            foreach ($raffleData as $raffle) {
                $retailStores = RetailStore::where('rto_code', $raffle->retail_store_code)->first();
                $cluster = $retailStores
                    ? RegionalCluster::where('cluster_id', $retailStores->cluster_id)->first()?->cluster_name
                    : null;

                $customer = Customers::where('customer_id', $raffle->customer_id)
                    ->join('product_lists', 'product_lists.product_id', '=', 'customers.product_purchased')
                    ->first();

                if ($retailStores && $cluster && $customer) {
                    $allData[] = [
                        'cluster' => $cluster,
                        'area' => $retailStores->area,
                        'address' => $retailStores->address,
                        'distributor' => $retailStores->distributor,
                        'retail_name' => $retailStores->retail_station,
                        'serial_number' => $raffle->serial_number,
                        'product_type' => $customer->product_name,
                        'customer_name' => $customer->full_name,
                        'customer_email' => $customer->email,
                        'customer_phone' => $customer->mobile_number,
                    ];
                }
            }
        }

        if (!empty($search)) {
            $allData = Tools::searchInArray($allData, $search);
        }

        if ($allDataFlag) {
            return response()->json(['data' => $allData]);
        }

        // Paginate for normal requests
        $totalRecords = count($allData);
        $filteredRecords = count($allData);
        $paginatedData = array_slice($allData, $start, $length);

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $paginatedData,
        ]);
    }
    public function getallevent()
    {
        $data = Event::orderBy('created_at', 'desc')->get(); // Order by latest created_at
        return response()->json($data);
    }


    public function addevent(Request $request)
    {
        $check = Event::where('event_status', 'Active')->first();
        if ($check) {
            return response()->json(['message' => 'There is still an ongoing raffle promo', 'success' => false]);
        }
        $event = new Event();
        $event->event_name = $request->event_name;
        $event->event_prize = $request->event_price;
        $event->event_start = $request->event_start;
        $event->event_end = $request->event_end;
        $event->event_description = $request->event_description;
        $event->event_status = 'Active';
        $event->save();

        $response = ['message' => 'Event added successfully', 'reload' => 'loadCard', 'success' => true];
        Tools::Logger($request, ['Added an Event', "Event {$request->event_name} has been added"], $response);

        return response()->json($response);
    }

    public function redraw(Request $request)
    {
        $raffleEntries = RaffleEntries::where('serial_number', $request->serial)->first();
        $raffleEntries->winner_status = 'false';
        $raffleEntries->save();

        $response = ['message' => 'Cluster winner disqualified. Prize will be redrawn on ' . $raffleEntries->updated_at, 'reload' => 'addWinnerRow', 'success' => true];
        Tools::Logger($request, ['Disqualify Customer', "A winner is unable to claim the prize and successfully remove its winner status"], $response);

        return response()->json($response);
    }

    public function getaselectedevent(Request $request)
    {
        $data = Event::where('event_id', $request->event_id)->first();
        return response()->json($data);
    }
    public function updateevent(Request $request)
    {

        $event = Event::where('event_id', $request->event_id)->where('event_status', 'Active')->first();
        if ($event) {
            $event->event_name = $request->event_name;
            $event->event_prize = $request->event_price;
            $event->event_start = $request->event_start;
            $event->event_end = $request->event_end;
            $event->event_description = $request->event_description;
            $event->save();

            $response = ['message' => 'Event successfully update', 'reload' => 'getevent', 'success' => true];
            Tools::Logger($request, ['Update Event Details', "Event is successfully Updated"], $response);

            return response()->json($response);
        }
        return response()->json(['message' => 'This event is currently inactive, and its details cannot be edited.', 'success' => false]);
    }

    public function inactiveevent(Request $request)
    {

        $user = User::where('id', Auth::id())->first();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Incorrect Password please try again']);
        }

        $event = Event::where('event_id', $request->event_id)->first();
        $event->event_status = 'Inactive';
        $event->save();

        $response = ['message' => 'Event successfully inactive', 'success' => true];
        Tools::Logger($request, ['Event Close', "Event is successfully set to inactive status"], $response);

        return response()->json($response);
    }


    public function productreport(Request $request)
    {
        // Fetch all events or filter by event_id
        $events = !empty($request->event_id)
            ? Event::where('event_id', $request->event_id)->get()
            : Event::all();

        $data = [];

        foreach ($events as $event) {
            // Fetch all customers for the event
            $customers = Customers::where('event_id', $event->event_id)->get();

            foreach ($customers as $customer) {
                // Filter RetailStore by region if provided
                $retailStoreQuery = RetailStore::where('store_id', $customer->store_id);
                if (!empty($request->region)) {
                    $retailStoreQuery->where('cluster_id', $request->region);
                }
                $retailStore = $retailStoreQuery->first();

                if (!$retailStore) continue; // Skip if no retail store matches

                // Fetch the cluster name
                $cluster = RegionalCluster::where('cluster_id', $retailStore->cluster_id)->first()?->cluster_name;

                // Filter ProductList by product type if provided
                $productQuery = ProductList::where('product_id', $customer->product_purchased);
                if (!empty($request->producttype)) {
                    $productQuery->where('product_id', $request->producttype);
                }
                $product = $productQuery->first();

                if (!$product) continue; // Skip if no product matches

                // Add data to the result
                $data[] = [
                    'cluster' => $cluster,
                    'area' => $retailStore->area ?? 'N/A',
                    'address' => $retailStore->address ?? 'N/A',
                    'distributor' => $retailStore->distributor ?? 'N/A',
                    'retail_name' => $retailStore->retail_station ?? 'N/A',
                    'purchase_date' => $customer->created_at ?? 'N/A',
                    'product' => $product->product_name ?? 'N/A',
                ];
            }
        }

        return response()->json($data);
    }
}
