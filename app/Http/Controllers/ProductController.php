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

            return response()->json(['success'=> true, 'message'=> 'Product has been successfully added']);

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

            return response()->json(['success'=> true, 'message'=> 'Product has been successfully update']);

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

            return response()->json(['success'=> true, 'message'=> $message]);

        }catch(ValidationException $e){

            return response()->json(['success'=> false, 'message'=> "Validation failed", 'error'=> $e->errors()]);

        }
    }

    public function list(){
        $products = ProductList::where('is_archived', false)->get();

        foreach($products as $product){

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

        return response()->json(['success'=> true, 'products'=> $products]);
    }

    public function search(Request $req){

        try{
            $req->validate([
                'search' => 'string|required|max:255',
            ]);

            $products = ProductList::where('product_name', 'like', "%$req->search%")->get();

            return response()->json(['success'=> true, 'products'=> $products]);

        }catch(ValidationException $e){

            return response()->json(['success'=> false, 'message'=> "Validation failed", 'error'=> $e->errors()]);

        }
    }

    public function details($prod_id){
        $product = ProductList::find($prod_id);

        if(!$product){
            return response()->json(['success'=> false, 'message'=> 'Product is not found in the database']);
        }

        return response()->json(['success'=> true, 'product'=> $product]);
    }

    public function archivelist(){
        $products = ProductList::where('is_archived', true)->get();

        return response()->json(['success'=> true, 'product'=> $products]);
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

            return response()->json(['success'=> true, 'message'=> 'Product successfully restore']);

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

            return response()->json(['success'=> true, 'products'=> $products]);

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


}
