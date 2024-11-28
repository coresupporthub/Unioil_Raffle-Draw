<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
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
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class RaffleController extends Controller
{
    public function getraffleentry(Request $request): JsonResponse
    {

        $event = Event::where('event_status', 'Active')->first();
        $retailStores = RetailStore::where('cluster_id', $request->id)->pluck('rto_code');
        $raffleEntries = RaffleEntries::where('winner_status', 'false')->where('winner_record', 'false')
            ->whereIn('retail_store_code', $retailStores)
            ->where('event_id', $event->event_id)
            ->get();

        return response()->json($raffleEntries);
    }

    public function raffledraw(Request $request): JsonResponse
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
            ->select('cluster_name', 'retail_station', 'distributor')->first();

        $product = ProductList::where('product_id', $customerWinner->product_purchased)->select('product_name')->first();

        $response = [
            'success' => true,
            'winner_serial_number' => $winnerSerialNumber,
            'winner_details' => $customerWinner,
            'cluster_name' => $store,
            'product' => $product
        ];

        $req = [
            'user_agent' => $request->userAgent(),
            'page_route' => $request->headers->get('referer'),
            'api_path' => $request->path(),
            'method' => $request->method(),
            'session_id' => $request->session()->getId(),
            'sent_data' => $request->all()
        ];
        Tools::Logger($req, ['Raffle Draw Stated', "Raffle Draw Selected a winner"], $response);
        return response()->json($response);
    }

    public function getallwinner(): JsonResponse
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

    public function geteventwinner(Request $request): JsonResponse
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
                'customer_age' => $customer->age,
                'cluster' => $cluster,
                'retail_code' => $retailStores->rto_code,
                'retail_area' => $retailStores->area,
                'retail_address'=> $retailStores->address,
                'retail_distributor' => $retailStores->distributor,
                'retail_name' => $retailStores->retail_station

            ];
        }
        return response()->json($data);
    }

    public function geteventunclaim(Request $request): JsonResponse
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

    public function validateclusterwinner(string $id): bool
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

    public function getallentry(Request $request): JsonResponse
    {
        $allDataFlag = $request->input('allData', false); // Check if all data is requested

        $start = $request->input('start', 0);
        $length =  $request->input('length', 10);
        $search =  $request->input('search')['value'];
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
                        'retail_code' => $retailStores->rto_code,
                        'serial_number' => $raffle->serial_number,
                        'product_type' => $customer->product_name,
                        'customer_name' => $customer->full_name,
                        'customer_email' => $customer->email,
                        'customer_age' => $customer->age,
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
    public function getallevent(): JsonResponse
    {
        $data = Event::orderBy('created_at', 'desc')->get(); // Order by latest created_at
        return response()->json($data);
    }


    public function addevent(Request $request): JsonResponse
    {
        $check = Event::where('event_status', 'Active')->first();
        if ($check) {
            return response()->json(['message' => 'There is still an ongoing raffle promo', 'success' => false]);
        }

        // Define the storage path
        $folderPath = 'event_images';

        // Call the reusable function to handle the upload
        if($request->file('image')->getSize()>10485760 || $request->file('banner')->getSize() > 10485760){
            return response()->json(['message' => 'Image size should not exceed 10MB', 'success' => false]);
        }
        $imageFileName = $this->storeFile($request->file('image'), $folderPath);
        $bannerFileName = $this->storeFile($request->file('banner'), $folderPath);

        // Create a new event
        $event = new Event();
        $event->event_name = $request->event_name;
        $event->event_prize = $request->event_prize;
        $event->event_start = $request->event_start;
        $event->event_end = $request->event_end;
        $event->event_prize_image = $imageFileName;
        $event->event_banner = $bannerFileName;
        $event->event_description = $request->event_description;
        $event->event_status = 'Active';
        $event->save();

        $response = ['message' => 'Event added successfully', 'reload' => 'loadCard', 'success' => true];
        $req = [
            'user_agent' => $request->userAgent(),
            'page_route' => $request->headers->get('referer'),
            'api_path' => $request->path(),
            'method' => $request->method(),
            'session_id' => $request->session()->getId(),
            'sent_data' => $request->all()
        ];
        Tools::Logger($req, ['Added an Event', "Event {$request->event_name} has been added"], $response);

        return response()->json($response);
    }

    public function storeFile($file, string $folder): string
    {

        $storagePath = storage_path("app/$folder");

        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);


            chown($storagePath, 'www-data');
            chgrp($storagePath, 'www-data');
        }

        $randomName = Str::random(10) . '.' . $file->getClientOriginalExtension();


        $file->move($storagePath, $randomName);


        return $randomName;
    }

    public function redraw(Request $request): JsonResponse
    {
        $raffleEntries = RaffleEntries::where('serial_number', $request->serial)->first();
        $raffleEntries->winner_status = 'false';
        $raffleEntries->save();

        $response = ['message' => 'Cluster winner disqualified. Prize will be redrawn on ' . $raffleEntries->updated_at, 'reload' => 'addWinnerRow', 'success' => true];
        $req = [
            'user_agent' => $request->userAgent(),
            'page_route' => $request->headers->get('referer'),
            'api_path' => $request->path(),
            'method' => $request->method(),
            'session_id' => $request->session()->getId(),
            'sent_data' => $request->all()
        ];
        Tools::Logger($req, ['Disqualify Customer', "A winner is unable to claim the prize and successfully remove its winner status"], $response);

        return response()->json($response);
    }

    public function getaselectedevent(Request $request): JsonResponse
    {
        // Retrieve the event data
        $data = Event::where('event_id', $request->event_id)->first();

        if ($data) {
            $prizeImagePath = storage_path('app/event_images/' . $data->event_prize_image);
            $data->event_prize_image = base64_encode(file_get_contents((string)$prizeImagePath));
            $bannerImagePath = storage_path('app/event_images/' . $data->event_banner);
            $data->event_banner = base64_encode(file_get_contents((string)$bannerImagePath));
        }

        // Return the event data as JSON
        return response()->json($data);
    }

    public function updateevent(Request $request): JsonResponse
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
            $req = [
                'user_agent' => $request->userAgent(),
                'page_route' => $request->headers->get('referer'),
                'api_path' => $request->path(),
                'method' => $request->method(),
                'session_id' => $request->session()->getId(),
                'sent_data' => $request->all()
            ];
            Tools::Logger($req, ['Update Event Details', "Event is successfully Updated"], $response);

            return response()->json($response);
        }
        return response()->json(['message' => 'This event is currently inactive, and its details cannot be edited.', 'success' => false]);
    }
    public function updateeventimages(Request $request): JsonResponse
    {
        $event = Event::where('event_id', $request->event_id)->where('event_status', 'Active')->first();
        if ($event) {
            $folderPath = 'event_images';

            $imageFileName = $this->storeFile($request->file('image'), $folderPath);

            $event->event_prize_image = $imageFileName;
            $event->save();

            $response = ['message' => 'Event successfully update', 'reload' => 'getevent', 'success' => true];
            $req = [
                'user_agent' => $request->userAgent(),
                'page_route' => $request->headers->get('referer'),
                'api_path' => $request->path(),
                'method' => $request->method(),
                'session_id' => $request->session()->getId(),
                'sent_data' => $request->all()
            ];
            Tools::Logger($req, ['Update Event Images', "Event is successfully Updated"], $response);

            return response()->json($response);
        }
        return response()->json(['message' => 'This event is currently inactive, and its details cannot be edited.', 'success' => false]);
    }

    public function updateeventbanner(Request $request): JsonResponse
    {
        $event = Event::where('event_id', $request->event_id)->where('event_status', 'Active')->first();
        if ($event) {

            $folderPath = 'event_images';

            $bannerFileName = $this->storeFile($request->file('banner'), $folderPath);
            $event->event_banner = $bannerFileName;
            $event->save();

            $response = ['message' => 'Event successfully update', 'reload' => 'getevent', 'success' => true];
            $req = [
                'user_agent' => $request->userAgent(),
                'page_route' => $request->headers->get('referer'),
                'api_path' => $request->path(),
                'method' => $request->method(),
                'session_id' => $request->session()->getId(),
                'sent_data' => $request->all()
            ];
            Tools::Logger($req, ['Update Event Images', "Event is successfully Updated"], $response);

            return response()->json($response);
        }
        return response()->json(['message' => 'This event is currently inactive, and its details cannot be edited.', 'success' => false]);
    }

    public function inactiveevent(Request $request): JsonResponse
    {

        $user = User::where('id', Auth::id())->first();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Incorrect Password please try again']);
        }

        $event = Event::where('event_id', $request->event_id)->first();
        $event->event_status = 'Inactive';
        $event->save();

        $response = ['message' => 'Event has been successfully set to inactive.', 'success' => true];
        $req = [
            'user_agent' => $request->userAgent(),
            'page_route' => $request->headers->get('referer'),
            'api_path' => $request->path(),
            'method' => $request->method(),
            'session_id' => $request->session()->getId(),
            'sent_data' => $request->all()
        ];
        Tools::Logger($req, ['Event Close', "Event is successfully set to inactive status"], $response);

        return response()->json($response);
    }


    public function productreport(Request $request): JsonResponse
    {
        // Fetch all events or filter by event_id
        $events = !empty($request->event_id)
            ? Event::where('event_id', $request->event_id)->get()
            : Event::all();

        $data = [];

        foreach ($events as $event) {

            $customers = Customers::where('event_id', $event->event_id)->get();

            foreach ($customers as $customer) {

                $retailStoreQuery = RetailStore::where('store_id', $customer->store_id);
                if (!empty($request->region)) {
                    $retailStoreQuery->where('cluster_id', $request->region);
                }
                $retailStore = $retailStoreQuery->first();

                if (!$retailStore) continue;


                $cluster = RegionalCluster::where('cluster_id', $retailStore->cluster_id)->first()?->cluster_name;


                $product = ProductList::where('product_id', $customer->product_purchased)
                ->when(!empty($request->ptype), function ($query) use ($request) {
                    $query->where('entries', $request->ptype);
                })
                ->when(!empty($request->producttype), function ($query) use ($request) {
                    $query->where('product_id', $request->producttype);
                })
                ->first();


                if (!$product) continue;


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
