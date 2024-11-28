<?php

namespace App\Http\Controllers;

use App\Http\Services\Tools;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\RegionalCluster;
use App\Models\RetailStore;

class RetailStoreController extends Controller
{

    public function addcluster(Request $request): JsonResponse{

        $data =  new RegionalCluster();
        $data->cluster_name = $request->cluster_name;
        $data->save();

        $response = ['success' => true , 'message'=>'Regional cluster successfully added', 'reload'=> 'LoadAll'];
        $req = [
            'user_agent' => $request->userAgent(),
            'page_route' => $request->headers->get('referer'),
            'api_path' => $request->path(),
            'method' => $request->method(),
            'session_id' => $request->session()->getId(),
        ];
        Tools::Logger($req, $request->all(), ['Add Regional Cluster', "Successfully add {$request->cluster_name} in the regional Cluster List"], $response);

        return response()->json($response);
    }

    public function getcluster(string $type): JsonResponse
    {
        if($type == 'all'){
            $data = RegionalCluster::orderBy('created_at', 'asc')->get();
        }else{
            $data = RegionalCluster::where('cluster_status', 'Enable')->orderBy('created_at', 'asc')->get(); // Order by 'created_at' in ascending order
        }

        return response()->json(['data' => $data]);
    }


    public function clusterstatus(Request $request): JsonResponse{
        $data = RegionalCluster::where('cluster_id', $request->id)->first();

        if(!$data){
            return response()->json(['success'=> false, 'message'=> 'No Regional Cluster Found']);
        }

        $data->update([
            'cluster_status' => 'Disable'
        ]);

        return response()->json(['success' => true , 'message'=>'Cluster status successfully delete', 'reload'=> 'LoadAll']);
    }


    public function getallstore(Request $request): JsonResponse{
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search')['value'];

        $query = RetailStore::join('regional_cluster', 'retail_store.cluster_id', '=', 'regional_cluster.cluster_id')
        ->select('regional_cluster.cluster_name', 'retail_store.*');

          if (!empty($search)) {
            $query->where('address', 'like', "%$search%")
                ->orWhere('cluster_name', 'like', "%$search%")
                ->orWhere('retail_station', 'like', "%$search%")
                ->orWhere('rto_code', 'like', "%$search%")
                ->orWhere('distributor', 'like', "%$search%");
        }

        $totalRecords = RetailStore::count();

        $filteredRecords = $query->count();

        $data = $query->skip($start)->take($length)->get();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function removeretailstore(Request $request): JsonResponse{
        $data = RetailStore::where('store_id', $request->id)->first();

        if(!$data){
            return response()->json(['success'=> false, 'message'=> 'No Retail Store Found']);
        }

        $response = ['success' => true, 'message' => 'Store status successfully delete'];
        $req = [
            'user_agent' => $request->userAgent(),
            'page_route' => $request->headers->get('referer'),
            'api_path' => $request->path(),
            'method' => $request->method(),
            'session_id' => $request->session()->getId(),
        ];
        Tools::Logger($req, $request->all(), ['Remove Retail Store', "Successfully deleted ".$data->retail_station. "in the Retail Store List"], $response);

        $data->delete();

        return response()->json($response);
    }

    public function updatestore(Request $request): JsonResponse
    {
        $data = RetailStore::where('store_id',$request->store_id)->first();
        if(!$data){
            return response()->json(['success'=> false, 'message'=> 'No retail store found']);
        }
        $data->cluster_id = $request->cluster_id;
        $data->area = $request->area;
        $data->address = $request->address;
        $data->distributor = $request->distributor;
        $data->retail_station = $request->retail_store;
        $data->rto_code = $request->rto_code;


        $response = ['success' => true, 'message' => 'Store status successfully update', 'reload' => 'LoadAll'];
        $req = [
            'user_agent' => $request->userAgent(),
            'page_route' => $request->headers->get('referer'),
            'api_path' => $request->path(),
            'method' => $request->method(),
            'session_id' => $request->session()->getId(),
        ];
        Tools::Logger($req, $request->all(), ['Update Retail Store', "Successfully updated from {$data->retail_station} to {$request->retail_store} in the Retail Store List"], $response);
        $data->save();
        return response()->json($response);
    }

    public function uploadcsv(Request $req): JsonResponse{
        $csv = $req->csv_file;

        if(empty($req->cluster)){
            return response()->json(['success' => false, 'message' => 'Please select a cluster']);
        }

        $csvData = Tools::readCSV($csv);


        array_shift($csvData);
        foreach($csvData as $data){
            $store = new RetailStore();

            if(!empty($data[0]) || !empty($data[1]) || !empty($data[2]) || !empty($data[3]) || !empty($data[4])){

                $checker = RetailStore::where('cluster_id', $req->cluster)
                                    ->where('area', $data[0])
                                    ->where('address', $data[1])
                                    ->where('distributor', $data[2])
                                    ->where('retail_station', $data[3])
                                    ->where('rto_code', $data[4])
                                    ->first();

                $codeChecker = RetailStore::where('rto_code', $data[4])->first();

                if($checker || $codeChecker){
                    continue;
                }

                $store->cluster_id = $req->cluster;
                $store->area = $data[0];
                $store->address = $data[1];
                $store->distributor = $data[2];
                $store->retail_station = $data[3];
                $store->rto_code = $data[4];
                $store->save();
            }

        }

        $response = ['success'=> true, 'message'=> 'All data are uploaded in the database'];
        $request = [
            'user_agent' => $req->userAgent(),
            'page_route' => $req->headers->get('referer'),
            'api_path' => $req->path(),
            'method' => $req->method(),
            'session_id' => $req->session()->getId(),
        ];
        Tools::Logger($request, $req->all(), ['Upload CSV File ', "Successfully Uploaded a CSV File in retail Store List"], $response);

        return response()->json($response);
    }

    public function filtercluster(Request $req): JsonResponse{
        $start = $req->input('start', 0);
        $length = $req->input('length', 10);
        $search = $req->input('search')['value'];

        $store = RetailStore::where('retail_store.cluster_id', $req->filter)->join('regional_cluster', 'retail_store.cluster_id', '=', 'regional_cluster.cluster_id')
        ->select('regional_cluster.cluster_name', 'retail_store.*');

        if (!empty($search)) {
            $store->where('address', 'like', "%$search%")
            ->orWhere('cluster_name', 'like', "%$search%")
            ->orWhere('retail_station', 'like', "%$search%")
            ->orWhere('rto_code', 'like', "%$search%")
            ->orWhere('distributor', 'like', "%$search%");
        }

        $totalRecords = RetailStore::count();

        $filteredRecords = $store->count();

        $data = $store->skip($start)->take($length)->get();

        return response()->json([
            'draw' => intval($req->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function addretailstore(Request $req): JsonResponse{
        $store = new RetailStore();

        $store->cluster_id = $req->cluster;
        $store->address = $req->address;
        $store->area = $req->area;
        $store->distributor = $req->distributor;
        $store->retail_station = $req->retail_station;
        $store->rto_code = $req->rto_code;

        $store->save();

        $response = ['success'=> true, 'message'=> "Retail station has been successfully added"];
        $request = [
            'user_agent' => $req->userAgent(),
            'page_route' => $req->headers->get('referer'),
            'api_path' => $req->path(),
            'method' => $req->method(),
            'session_id' => $req->session()->getId(),
        ];
        Tools::Logger($request, $req->all(), ['Added A Retail Store', "Successfully added a retail store"], $response);

        return response()->json($response);
    }

    public function updatecluster(Request $req): JsonResponse{
        $cluster = RegionalCluster::where('cluster_id', $req->cluster_id)->first();

        if(!$cluster){
            return response()->json(['success'=> false, 'message'=> 'No Valid Cluster Found using this id']);
        }

        $cluster->update([
            'cluster_name'=> $req->cluster_name
        ]);

        return response()->json(['success'=> true, 'message'=> 'Regional Cluster Name Updated']);
    }

    public function enablecluster(Request $req): JsonResponse{
        $cluster = RegionalCluster::where('cluster_id', $req->id)->first();

        if(!$cluster){
            return response()->json(['success'=> false, 'message'=> 'No Valid Cluster Found using this id']);
        }

        $cluster->update([
            'cluster_status'=> 'Enable'
        ]);

        return response()->json(['success'=> true, 'message'=> 'Regional Cluster Enabled']);
    }
}
