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
use App\Models\QrCode;
use App\Http\Services\Tools;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use App\Http\Services\Magic;

class RaffleController extends Controller
{
    public function getraffleentry(Request $request): JsonResponse
    {

        $event = Event::where('event_status', 'Active')->first();

        if(!$event){
            return response()->json(['success'=> false, 'No Active Event Found', 'data'=> []]);
        }

        $retailStores = RetailStore::where('cluster_id', $request->id)->pluck('rto_code');
        $raffleEntries = RaffleEntries::where('winner_status', 'false')->where('winner_record', 'false')
            ->whereIn('retail_store_code', $retailStores)
            ->where('event_id', $event->event_id)
            ->get();


        return response()->json(['data'=> $raffleEntries]);
    }

    public function raffledraw(Request $request): JsonResponse
    {

        $check = $this->validateclusterwinner($request->id);
        $cluster_name = RegionalCluster::where('cluster_id', $request->id)->first();
        if ($check && $cluster_name) {
            return response()->json([
                'success' => false,
                'message' => $cluster_name->cluster_name . ' already have a winner',
            ]);
        }

        $event = Event::where('event_status', 'Active')->first();
        if(!$event){
            return response()->json(['success'=> false, 'message'=> 'No Active Event Found']);
        }
        $retailStores = RetailStore::where('cluster_id', $request->id)->pluck('rto_code');
        $raffleEntries = RaffleEntries::where('winner_status', 'false')->where('winner_record', 'false')
            ->whereIn('retail_store_code', $retailStores)
            ->where('event_id', $event->event_id)
            ->select('serial_number')
            ->get();

        if(count($raffleEntries) == 0){
            return response()->json(['success'=> false, 'message'=> 'No Raffle Entries Found']);
        }

        $shuffledSerialNumbers = $raffleEntries->pluck('serial_number')->toArray();
        shuffle($shuffledSerialNumbers);

        $winnerSerialNumber = $shuffledSerialNumbers[0];

        $winnerRaffleEntry = RaffleEntries::where('serial_number', $winnerSerialNumber)->first();

        if(!$winnerRaffleEntry){
            return response()->json(['success'=> false, 'message'=> 'No raffle Entry Found']);
        }

        $winnerRaffleEntry->winner_status = 'true';
        $winnerRaffleEntry->winner_record = 'true';
        $winnerRaffleEntry->save();

        $customerWinner = Customers::where('customer_id', $winnerRaffleEntry->customer_id ?? null)->first();
        $store = RetailStore::where('store_id', $customerWinner->store_id ?? null)->join('regional_cluster', 'retail_store.cluster_id', '=', 'regional_cluster.cluster_id')
            ->select('cluster_name', 'retail_station', 'distributor')->first() ?? null;

        $product = ProductList::where('product_id', $customerWinner->product_purchased ?? null)->select('product_name')->first();

        if(!$product){
            return response()->json(['success'=> false, 'message'=> 'No Product Found']);
        }

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
        ];
        Tools::Logger($req, $request->all(), ['Raffle Draw Stated', "Raffle Draw Selected a winner"], $response);
        return response()->json($response);
    }

    public function getallwinner(): JsonResponse
    {

        $event = Event::where('event_status', 'Active')->first();

        if(!$event){
            return response()->json(['success'=> false, 'message'=> 'No Active Event Found', 'data' => []]);
        }

        $raffleEntries = RaffleEntries::where('winner_status', 'true')
            ->where('event_id', $event->event_id)
            ->get();
        $data = [];
        foreach ($raffleEntries as $entry) {
            $retailStores = RetailStore::where('rto_code', $entry->retail_store_code ?? null)->first();
            $cluster = RegionalCluster::where('cluster_id', $retailStores->cluster_id ?? null)->first();
            $customer = Customers::where('customer_id', $entry->customer_id ?? null)->first();
            $data[] = [
                'event_prize' => $event->event_prize ?? null,
                'serial_number' => $entry->serial_number ?? null,
                'customer_name' => $customer->full_name ?? null,
                'customer_email' => $customer->email ?? null,
                'customer_number' => $customer->mobile_number ?? null,
                'cluster' => $cluster->cluster_name ?? null
            ];
        }
        return response()->json(['data'=> $data]);
    }

    public function geteventwinner(Request $request): JsonResponse
    {

        $event = Event::where('event_id', $request->event_id)->first();

        if(!$event){
            return response()->json(['success'=> false, 'message'=> 'No Active Event Found']);
        }

        $raffleEntries = RaffleEntries::where('winner_status', 'true')
            ->where('event_id', $event->event_id)
            ->get();
        $data = [];
        foreach ($raffleEntries as $entry) {
            $retailStores = RetailStore::where('rto_code', $entry->retail_store_code ?? null)->first();
            $cluster = RegionalCluster::where('cluster_id', $retailStores->cluster_id ?? null)->first();

            $customer = Customers::where('customer_id', $entry->customer_id ?? null)->first();
            $data[] = [
                'event_prize' => $event->event_prize ?? null,
                'serial_number' => $entry->serial_number,
                'customer_name' => $customer->full_name ?? null,
                'customer_email' => $customer->email ?? null,
                'customer_number' => $customer->mobile_number ?? null,
                'customer_age' => $customer->age ?? null,
                'cluster' => $cluster->cluster_name ?? null,
                'retail_code' => $retailStores->rto_code ?? null,
                'retail_area' => $retailStores->area ?? null,
                'retail_address' => $retailStores->address ?? null,
                'retail_distributor' => $retailStores->distributor ?? null,
                'retail_name' => $retailStores->retail_station ?? null

            ];
        }
        return response()->json($data);
    }

    public function geteventunclaim(Request $request): JsonResponse
    {

        $event = Event::where('event_id', $request->event_id)->first();
        if(!$event){
            return response()->json(['success'=> false, 'message'=> 'No event Found']);
        }
        $raffleEntries = RaffleEntries::where('winner_status', 'false')
            ->where('winner_record', 'true')
            ->where('event_id', $event->event_id)
            ->get();
        $data = [];
        foreach ($raffleEntries as $entry) {
            $retailStores = RetailStore::where('rto_code', $entry->retail_store_code ?? null)->first();
            $cluster = RegionalCluster::where('cluster_id', $retailStores->cluster_id ?? null)->first();
            $customer = Customers::where('customer_id', $entry->customer_id ?? null)->first();
            $data[] = [
                'event_prize' => $event->event_prize ?? null,
                'serial_number' => $entry->serial_number,
                'customer_name' => $customer->full_name ?? null,
                'customer_email' => $customer->email ?? null,
                'customer_number' => $customer->mobile_number ?? null,
                'cluster' => $cluster->cluster_name ?? null
            ];
        }
        return response()->json($data);
    }

    public function validateclusterwinner(string $id): bool
    {

        $event = Event::where('event_status', 'Active')->first();
        if(!$event){
            return false;
        }
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
        $search =  $request->input('search')['value'] ?? null;
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
                    ->select('customers.*', 'product_lists.product_name as product_name')
                    ->first();

                $qrCode = QrCode::find($raffle->qr_id);

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
                        'entry_id' => $raffle->entries_id,
                        'created_at' => $raffle->created_at,
                        'qr_code' => $qrCode->code
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

        $folderPath = 'event_images';

        $imageFile = $request->file('image');
        $bannerFile = $request->file('banner');


        if (is_array($imageFile)) {
            $imageFile = $imageFile[0] ?? null;
        }

        if (is_array($bannerFile)) {
            $bannerFile = $bannerFile[0] ?? null;
        }

        if (
            ($imageFile instanceof UploadedFile && $imageFile->getSize() > 10485760) ||
            ($bannerFile instanceof UploadedFile && $bannerFile->getSize() > 10485760)) {
            return response()->json(['message' => 'Image size should not exceed 10MB', 'success' => false]);
        }

        $imageFileName = $this->storeFile($imageFile, $folderPath);
        $bannerFileName = $this->storeFile($bannerFile, $folderPath);

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
        $event->event_prize_disclaimer = $request->disclaimer ?? null;
        $event->save();

        $response = ['message' => 'Event added successfully', 'reload' => 'loadCard', 'success' => true];
        $req = [
            'user_agent' => $request->userAgent(),
            'page_route' => $request->headers->get('referer'),
            'api_path' => $request->path(),
            'method' => $request->method(),
            'session_id' => $request->session()->getId(),
        ];
        Tools::Logger($req, $request->all(), ['Added an Event', "Event {$request->event_name} has been added"], $response);

        return response()->json($response);
    }

    public function storeFile(?UploadedFile $file, string $folder): string
    {

        $storagePath = storage_path("app/$folder");

        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);


            chown($storagePath, 'www-data');
            chgrp($storagePath, 'www-data');
        }

        if ($file instanceof UploadedFile) {
            $randomName = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move($storagePath, $randomName);
        } else {
            throw new \Exception('No valid file provided to upload.');
        }
        return $randomName;
    }

    public function redraw(Request $request): JsonResponse
    {
        $raffleEntries = RaffleEntries::where('serial_number', $request->serial)->first();

        if(!$raffleEntries){
            return response()->json(['success'=> false, 'message'=> 'No Raffle Entry Found']);
        }

        $raffleEntries->winner_status = 'false';
        $raffleEntries->save();

        $response = ['message' => 'Cluster winner disqualified. Prize will be redrawn on ' . $raffleEntries->updated_at, 'reload' => 'addWinnerRow', 'success' => true];
        $req = [
            'user_agent' => $request->userAgent(),
            'page_route' => $request->headers->get('referer'),
            'api_path' => $request->path(),
            'method' => $request->method(),
            'session_id' => $request->session()->getId(),
        ];
        Tools::Logger($req, $request->all(), ['Disqualify Customer', "A winner is unable to claim the prize and successfully remove its winner status"], $response);

        return response()->json($response);
    }

    public function getaselectedevent(Request $request): JsonResponse
    {
        // Retrieve the event data
        $data = Event::where('event_id', $request->event_id)->first();

        if ($data) {
            $data->event_prize_image = base64_encode((string)file_get_contents(storage_path('app/event_images/' . $data->event_prize_image)));
            $data->event_banner = base64_encode((string)file_get_contents(storage_path('app/event_images/' . $data->event_banner)));
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
            ];
            Tools::Logger($req, $request->all(), ['Update Event Details', "Event is successfully Updated"], $response);

            return response()->json($response);
        }
        return response()->json(['message' => 'This event is currently inactive, and its details cannot be edited.', 'success' => false]);
    }
    public function updateeventimages(Request $request): JsonResponse
    {
        $event = Event::where('event_id', $request->event_id)->where('event_status', 'Active')->first();
        if ($event) {
            $folderPath = 'event_images';

            $image = $request->file('image') ?? null;
            if(is_array($image)){
                $image = $image[0] ?? null;
            }
            $imageFileName = $this->storeFile($image, $folderPath);

            $event->event_prize_image = $imageFileName;
            $event->save();

            $response = ['message' => 'Event successfully update', 'reload' => 'getevent', 'success' => true];
            $req = [
                'user_agent' => $request->userAgent(),
                'page_route' => $request->headers->get('referer'),
                'api_path' => $request->path(),
                'method' => $request->method(),
                'session_id' => $request->session()->getId(),
            ];
            Tools::Logger($req, $request->all(), ['Update Event Images', "Event is successfully Updated"], $response);

            return response()->json($response);
        }
        return response()->json(['message' => 'This event is currently inactive, and its details cannot be edited.', 'success' => false]);
    }

    public function updateeventbanner(Request $request): JsonResponse
    {
        $event = Event::where('event_id', $request->event_id)->where('event_status', 'Active')->first();
        if ($event) {

            $folderPath = 'event_images';

            $bannerImage = $request->file('banner');

            if(is_array($bannerImage)){
                $bannerImage = $bannerImage[0];
            }
            $bannerFileName = $this->storeFile($bannerImage, $folderPath);
            $event->event_banner = $bannerFileName;
            $event->save();

            $response = ['message' => 'Event successfully update', 'reload' => 'getevent', 'success' => true];
            $req = [
                'user_agent' => $request->userAgent(),
                'page_route' => $request->headers->get('referer'),
                'api_path' => $request->path(),
                'method' => $request->method(),
                'session_id' => $request->session()->getId(),
            ];
            Tools::Logger($req, $request->all(), ['Update Event Images', "Event is successfully Updated"], $response);

            return response()->json($response);
        }
        return response()->json(['message' => 'This event is currently inactive, and its details cannot be edited.', 'success' => false]);
    }

    public function inactiveevent(Request $request): JsonResponse
    {

        $user = User::where('id', Auth::id())->first();

        if(!$user){
            return response()->json(['success'=> false, 'message'=> 'No User Found']);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Incorrect Password please try again']);
        }

        $event = Event::where('event_id', $request->event_id)->first();
        if(!$event){
            return response()->json(['success'=> false, 'message'=> 'No Event Found']);
        }

        $event->event_status = 'Inactive';
        $event->save();

        $response = ['message' => 'Event has been successfully set to inactive.', 'success' => true];
        $req = [
            'user_agent' => $request->userAgent(),
            'page_route' => $request->headers->get('referer'),
            'api_path' => $request->path(),
            'method' => $request->method(),
            'session_id' => $request->session()->getId(),
        ];
        Tools::Logger($req, $request->all(), ['Event Close', "Event is successfully set to inactive status"], $response);

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

    public function removeentry(Request $req){
        $entry = RaffleEntries::find($req->id);

        $qr = QrCode::find($entry->qr_id);

        $customerId = $entry->customer_id;

        $customer = Customers::find($customerId);

        $qr->update([
            'status' => Magic::QR_UNUSED
        ]);

        $entry->delete();

        $customer->delete();

        return response()->json(['success'=> true, 'message'=> 'Raffle Entry has been successfully deleted']);
    }
}
