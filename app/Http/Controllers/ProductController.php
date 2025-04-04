<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductList;
use App\Http\Services\Magic;
use App\Models\Customers;
use Illuminate\Validation\ValidationException;
use App\Models\Event;
use App\Models\RegionalCluster;
use Illuminate\Support\Facades\Storage;
use App\Http\Services\Tools;
class ProductController extends Controller
{
    public function add(Request $req){

        try{

            $req->validate([
                'name' => 'string|required',
                'type' => 'string|required',
                'entry' => 'integer|required'
            ]);

            $checkName = ProductList::where('product_name', $req->name)->first();

            if($checkName){
                return response()->json(['success'=> false, 'message' => 'Product Name already exist please choose different one']);
            }

            $image = $req->file('image') ?? null;

            if($image){

                if($image->getSize() > Magic::MAX_IMAGE_SIZE){
                    return response()->json(['success'=> false, 'message' => 'Image is too big please choose below 5 mb image']);
                }

                if(!in_array($image->getClientOriginalExtension(), Magic::ACCEPTED_IMAGE_TYPE)){
                    return response()->json(['success'=> false, 'message' => 'Image type is invalid we only accepts jpg, jpeg and png']);
                }

            }

            $product = new ProductList();
            $product->product_name = $req->name;
            $product->product_type = $req->type;
            $product->entries = $req->entry;
            $product->save();

            if($image){
                $fileName = 'Product-' . $product->product_id . "." . $image->getClientOriginalExtension();

                $updateImage = ProductList::find($product->product_id);

                $updateImage->update([
                    'product_image' => $fileName
                ]);

                $image->storeAs(self::PRODUCT_PATH,  $fileName);
            }

            $response = ['success'=> true, 'message'=> 'Product has been successfully added'];

            $request = [
                'user_agent' => $req->userAgent(),
                'page_route' => $req->headers->get('referer'),
                'api_path' => $req->path(),
                'method' => $req->method(),
                'session_id' => $req->session()->getId(),
            ];

            Tools::Logger($request, $req->all(), ['Add New Products', "Successfully added $req->name in the product list"], $response);

            return response()->json($response);

        }catch(ValidationException $e){

            return response()->json(['success'=> false, 'message'=> "Validation failed", 'error'=> $e->errors()]);

        }

    }

    public function update(Request $req){
        try{

            $req->validate([
                'id' => 'string|required',
                'name' => 'string|required',
                'type' => 'string|required',
                'entry' => 'integer|required'
            ]);

            $checkName = ProductList::where('product_name', $req->name)->where('product_id', '!=', $req->id)->first();

            if($checkName){
                return response()->json(['success'=> false, 'message' => 'Product Name already exist please choose different one']);
            }

            $product = ProductList::find($req->id);

            if(!$product){
                return response()->json(['success'=> false, 'message' => 'Product does not exist']);
            }

            $product->update([
                'product_name' => $req->name,
                'product_type' => $req->type,
                'entries' => $req->entry
            ]);

            $response = ['success'=> true, 'message'=> 'Product has been successfully update'];

            $request = [
                'user_agent' => $req->userAgent(),
                'page_route' => $req->headers->get('referer'),
                'api_path' => $req->path(),
                'method' => $req->method(),
                'session_id' => $req->session()->getId(),
            ];

            Tools::Logger($request, $req->all(), ['Update Product', "Successfully updated $req->name in the product list"], $response);

            return response()->json($response);


        }catch(ValidationException $e){

            return response()->json(['success'=> false, 'message'=> "Validation failed", 'error'=> $e->errors()]);

        }
    }

    public function remove(Request $req){
        try{

            $req->validate([
                'product_id' => 'string|required',
            ]);

            $product = ProductList::find($req->product_id);

            if(!$product){
                return response()->json(['success'=> false, 'message'=> 'Product is not found in the database']);
            }

            $customer = Customers::where('product_purchased', $req->product_id)->count();

            $message = "Product is successfully deleted";
            if($customer > 0){
                $product->update([
                    'is_archived'=> true,
                ]);

                $message = "Product has data attached and it has been successfully archived";
            }else{
                $product->delete();
            }

            $response = ['success'=> true, 'message'=> $message];

            $request = [
                'user_agent' => $req->userAgent(),
                'page_route' => $req->headers->get('referer'),
                'api_path' => $req->path(),
                'method' => $req->method(),
                'session_id' => $req->session()->getId(),
            ];

            Tools::Logger($request, $req->all(), ['Remove Product', $message], $response);

            return response()->json($response);

        }catch(ValidationException $e){

            return response()->json(['success'=> false, 'message'=> "Validation failed", 'error'=> $e->errors()]);

        }
    }

    public function list(){
        $products = ProductList::where('is_archived', false)->get();

        $totalPurchased = Customers::count();

        foreach($products as $product){
            $product->purchased = Customers::where('product_purchased', $product->product_id)->count();

            if(!$product->product_image) continue;

            $filePath = "product_logo/$product->product_image";

            if (!Storage::exists($filePath)) {
                return response()->json(['success'=> false, 'message' => 'Image not found'], 404);
            }

            $fileContents = Storage::get($filePath);

            $mimeType = Storage::mimeType($filePath);
            $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode($fileContents);


            $product->imagebase64 = $base64Image;
        }

        $sortedProducts = $products->sortByDesc('purchased')->values();

        return response()->json(['success'=> true, 'products'=> $sortedProducts, 'total_products'=> $totalPurchased]);
    }

    public function search(Request $req){

        try{
            $req->validate([
                'search' => 'string|required|max:255',
            ]);

            $products = ProductList::where('product_name', 'like', "%$req->search%")->get();

            $totalPurchased = 0;

            foreach($products as $product){
                $totalPurchased += Customers::where('product_purchased', $product->product_id)->count();

                if(!$product->product_image) continue;

                $filePath = "product_logo/$product->product_image";

                if (!Storage::exists($filePath)) {
                    return response()->json(['success'=> false, 'message' => 'Image not found'], 404);
                }

                $fileContents = Storage::get($filePath);

                $product->purchased = Customers::where('product_purchased', $product->product_id)->count();

                $mimeType = Storage::mimeType($filePath);
                $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode($fileContents);

                $product->imagebase64 = $base64Image;
            }


            return response()->json(['success'=> true, 'products'=> $products, 'total_products'=> $totalPurchased]);

        }catch(ValidationException $e){

            return response()->json(['success'=> false, 'message'=> "Validation failed", 'error'=> $e->errors()]);

        }
    }

    public function details($prod_id){
        $product = ProductList::find($prod_id);

        if(!$product){
            return response()->json(['success'=> false, 'message'=> 'Product is not found in the database']);
        }

        if($product->product_image){
            $filePath = "product_logo/$product->product_image";

            if (!Storage::exists($filePath)) {
                return response()->json(['success'=> false, 'message' => 'Image not found'], 404);
            }

            $fileContents = Storage::get($filePath);

            $mimeType = Storage::mimeType($filePath);
            $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode($fileContents);

            $product->imagebase64 = $base64Image;
        }

        return response()->json(['success'=> true, 'product'=> $product]);
    }

    public function archivelist(){
        $products = ProductList::where('is_archived', true)->get();

        $totalPurchased = 0;
        foreach($products as $product){
            $totalPurchased += Customers::where('product_purchased', $product->product_id)->count();

            if(!$product->product_image) continue;

            $filePath = "product_logo/$product->product_image";

            if (!Storage::exists($filePath)) {
                return response()->json(['success'=> false, 'message' => 'Image not found'], 404);
            }

            $fileContents = Storage::get($filePath);

            $product->purchased = Customers::where('product_purchased', $product->product_id)->count();

            $mimeType = Storage::mimeType($filePath);
            $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode($fileContents);

            $product->imagebase64 = $base64Image;
        }


        return response()->json(['success'=> true, 'product'=> $products, 'total_products' => $totalPurchased]);
    }

    public function restore(Request $req){
        try{
            $req->validate([
                'id' => 'string|required',
            ]);

            $product = ProductList::find($req->id);

            if(!$product){
                return response()->json(['success'=> false, 'message'=> 'Product is not found in the database']);
            }

            $product->update([
                'is_archived' => false
            ]);

            $response =['success'=> true, 'message'=> 'Product successfully restore'];

            $request = [
                'user_agent' => $req->userAgent(),
                'page_route' => $req->headers->get('referer'),
                'api_path' => $req->path(),
                'method' => $req->method(),
                'session_id' => $req->session()->getId(),
            ];

            Tools::Logger($request, $req->all(), ['Remove Product', "Archived Product has been successfully restored"], $response);

            return response()->json($response);

        }catch(ValidationException $e){

            return response()->json(['success'=> false, 'message'=> "Validation failed", 'error'=> $e->errors()]);

        }
    }

    public function searcharchived(Request $req){
        try{
            $req->validate([
                'search' => 'string|required|max:255',
            ]);

            $products = ProductList::where('is_archived', true)->where('product_name', 'like', "%$req->search%")->get();

            $totalPurchased = 0;
            foreach($products as $product){
                $totalPurchased += Customers::where('product_purchased', $product->product_id)->count();

                if(!$product->product_image) continue;

                $filePath = "product_logo/$product->product_image";

                if (!Storage::exists($filePath)) {
                    return response()->json(['success'=> false, 'message' => 'Image not found'], 404);
                }

                $fileContents = Storage::get($filePath);

                $product->purchased = Customers::where('product_purchased', $product->product_id)->count();

                $mimeType = Storage::mimeType($filePath);
                $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode($fileContents);

                $product->imagebase64 = $base64Image;
            }

            return response()->json(['success'=> true, 'products'=> $products, 'total_products'=> $totalPurchased]);

        }catch(ValidationException $e){

            return response()->json(['success'=> false, 'message'=> "Validation failed", 'error'=> $e->errors()]);

        }
    }

    public function reports(Request $req){
        $start = $req->input('start', 0);
        $length = $req->input('length', 10);
        $regionalCluster = $req->cluster;
        $event = $req->event;
        $productId = $req->id;
        $search = $req->input('search')['value'];

        if($event == 'all'){
            $getEvent = Event::all();
            $reports = $this->getReports('all', $getEvent, $start, $length, $regionalCluster, $productId, $search);
        }else{
            $getEvent = Event::find($event);
            $reports = $this->getReports('event', $getEvent,  $start, $length, $regionalCluster, $productId, $search);
        }


        return response()->json([
             'draw' => intval($req->input('draw')),
             'recordsTotal' => $reports[1],
             'recordsFiltered' => $reports[1],
             'data' => $reports[0]
        ]);
    }

    private function getReports($type, $events,  $start, $length, $regionalCluster, $productId, $search){
        $data = [];
        if($type == 'all'){
            foreach($events as $event){

                if($regionalCluster == 'all'){
                    $allRecords = Customers::where('event_id', $event->event_id)->where('product_purchased', $productId)
                    ->join('retail_store', 'customers.store_id', '=', 'retail_store.store_id')->where('retail_store.cluster_id', $regionalCluster)->count();
                    $query = Customers::where('event_id', $event->event_id)->where('product_purchased', $productId)
                    ->join('retail_store', 'customers.store_id', '=', 'retail_store.store_id');
                }else{
                    $allRecords = Customers::where('event_id', $event->event_id)->where('product_purchased', $productId)
                    ->join('retail_store', 'customers.store_id', '=', 'retail_store.store_id')->where('retail_store.cluster_id', $regionalCluster)->count();
                    $query = Customers::where('event_id', $event->event_id)->where('product_purchased', $productId)
                    ->join('retail_store', 'customers.store_id', '=', 'retail_store.store_id')->where('retail_store.cluster_id', $regionalCluster);
                }


                if (!empty($search)) {
                    $query->where('retail_store.address', 'like', "%$search%")
                        ->orWhere('retail_store.area', 'like', "%$search%")
                        ->orWhere('retail_store.distributor', 'like', "%$search%")
                        ->orWhere('retail_store.retail_station', 'like', "%$search%");
                }

                $customers = $query->skip($start)->take($length)->get();

                foreach ($customers as $customer) {

                    $cluster = RegionalCluster::where('cluster_id', $customer->cluster_id)->first()->cluster_name;

                    $data[] = [
                        'cluster' => $cluster,
                        'area' => $customer->area ?? 'N/A',
                        'address' => $customer->address ?? 'N/A',
                        'distributor' => $customer->distributor ?? 'N/A',
                        'retail_name' => $customer->retail_station ?? 'N/A',
                        'purchase_date' => $customer->created_at ?? 'N/A',
                    ];
                }
            }
        }else{
            $allRecords = Customers::where('event_id', $events->event_id)->where('product_purchased', $productId)->count();
            if($regionalCluster == 'all'){
                $allRecords = Customers::where('event_id', $events->event_id)->where('product_purchased', $productId)
                ->join('retail_store', 'customers.store_id', '=', 'retail_store.store_id')->count();
                $query = Customers::where('event_id', $events->event_id)->where('product_purchased', $productId)
                ->join('retail_store', 'customers.store_id', '=', 'retail_store.store_id');
            }else{
                $allRecords = Customers::where('event_id', $events->event_id)->where('product_purchased', $productId)
                ->join('retail_store', 'customers.store_id', '=', 'retail_store.store_id')->where('retail_store.cluster_id', $regionalCluster)->count();
                $query = Customers::where('event_id', $events->event_id)->where('product_purchased', $productId)
                ->join('retail_store', 'customers.store_id', '=', 'retail_store.store_id')->where('retail_store.cluster_id', $regionalCluster);
            }

            if (!empty($search)) {
                $query->where('retail_store.address', 'like', "%$search%")
                    ->orWhere('retail_store.area', 'like', "%$search%")
                    ->orWhere('retail_store.distributor', 'like', "%$search%")
                    ->orWhere('retail_store.retail_station', 'like', "%$search%");
            }

            $customers = $query->skip($start)->take($length)->get();

            foreach ($customers as $customer) {

                $cluster = RegionalCluster::where('cluster_id', $customer->cluster_id)->first()->cluster_name;

                $data[] = [
                    'customer_name' => $customer->full_name,
                    'cluster' => $cluster,
                    'area' => $customer->area ?? 'N/A',
                    'address' => $customer->address ?? 'N/A',
                    'distributor' => $customer->distributor ?? 'N/A',
                    'retail_name' => $customer->retail_station ?? 'N/A',
                    'purchase_date' => $customer->created_at ?? 'N/A',
                ];
            }
        }


        return [$data, $allRecords];
    }

    public function uploadlogo(Request $req){

        if(!$req->id){
            return response()->json(['success'=> false, 'message'=> 'Product ID is required']);
        }

        $image = $req->file('image');

        if(!$image){
            return response()->json(['success'=> false, 'message'=> 'Image is not found']);
        }

        if($image->getSize() > Magic::MAX_IMAGE_SIZE){
            return response()->json(['success'=> false, 'message' => 'Image is too big please choose below 5 mb image']);
        }

        if(!in_array($image->getClientOriginalExtension(), Magic::ACCEPTED_IMAGE_TYPE)){
            return response()->json(['success'=> false, 'message' => 'Image type is invalid we only accepts jpg, jpeg and png']);
        }


        $fileName = 'Product-' . $req->id . "." . $image->getClientOriginalExtension();

        $updateImage = ProductList::find($req->id);

        $updateImage->update([
            'product_image' => $fileName
        ]);

        $image->storeAs(self::PRODUCT_PATH,  $fileName);

        $response =['success'=> true, 'message'=> 'Product image has been successfully updated'];

            $request = [
                'user_agent' => $req->userAgent(),
                'page_route' => $req->headers->get('referer'),
                'api_path' => $req->path(),
                'method' => $req->method(),
                'session_id' => $req->session()->getId(),
        ];

        Tools::Logger($request, $req->all(), ['Update Logo', "Products Logo has been successfully updated"], $response);

        return response()->json($response);
    }

    public function chartreports(){
        $products = ProductList::all();

        $productArray = [];
        $productData = [];
        $dates = Customers::all()
        ->pluck('created_at')
        ->map(fn($date) => $date->format('Y-m-d'))
        ->unique()
        ->sort()
        ->values();

        $lineData = [];

        foreach($products as $product){
            $customerCount = Customers::where('product_purchased', $product->product_id)->count();
            $productArray[] = $product->product_name;
            $productData[] = $customerCount;

            $initialLineData = [];
            foreach($dates as $date){
                $customerData = Customers::where('product_purchased', $product->product_id)->whereDate('created_at', $date)->count();
                $initialLineData[] = $customerData;
            }

            $data['name'] = $product->product_name;
            $data['data'] = $initialLineData;

            $lineData[] = $data;
        }

        return response()->json([
            'status' => true,
            'barchart' => [
                'products' => $productArray,
                'data' => $productData
            ],
            'piechart' => [
                'products' => $productArray,
                'data' => $productData
            ],
            'linechart' => [
                'dates' => $dates,
                'data' => $lineData
            ]

            ]);
    }


    public function productreports(Request $req){
        $start = $req->input('start', 0);
        $length = $req->input('length', 10);
        $regionalCluster = $req->cluster;
        $event = $req->event;
        $product = $req->product;
        $search = $req->input('search')['value'];

        $data = [];

        if($event === 'all'){
            $query = Customers::query();
            $totalRecords = Customers::count();
        }else{
            $query = Customers::where('event_id', $event);
            $totalRecords = Customers::where('event_id', $event)->count();
        }


        if($product !== "all"){
            $query->where('product_purchased', $product);
        }

        $query->join('retail_store', 'customers.store_id', '=', 'retail_store.store_id');

        if($regionalCluster != 'all'){
            $query->where('retail_store.cluster_id', $regionalCluster);
        }

        $query->join('product_lists', 'customers.product_purchased', '=', 'product_lists.product_id')
        ->join('regional_cluster', 'regional_cluster.cluster_id', '=', 'retail_store.cluster_id');

        if (!empty($search)) {
            $query->where('retail_store.address', 'like', "%$search%")
                ->orWhere('retail_store.area', 'like', "%$search%")
                ->orWhere('retail_store.distributor', 'like', "%$search%")
                ->orWhere('retail_store.retail_station', 'like', "%$search%")
                ->orWhere('product_lists.product_name', 'like', "%$search%");
        }


        $filter = $query->count();
        $query->select(
            'retail_store.address',
            'retail_store.area',
            'retail_store.distributor',
            'retail_store.retail_station',
            'product_lists.product_name',
            'customers.full_name',
            'customers.created_at',
            'regional_cluster.cluster_name',
        );
        $data = $query->skip($start)->take($length)->get();

        return response()->json([
             'draw' => intval($req->input('draw')),
             'recordsTotal' => $totalRecords,
             'recordsFiltered' => $filter,
             'data' => $data
        ]);
    }

}
