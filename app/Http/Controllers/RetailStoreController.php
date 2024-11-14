<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;
use App\Models\RegionalCluster;
use App\Models\City;

class RetailStoreController extends Controller
{
    
    public function addcluster(request $request){

        $data =  new RegionalCluster();
        $data->cluster_name = $request->cluster_name;
        $data->save();

        return response()->json(['success' => true , 'message'=>'Regional cluster successfully added', 'reload'=> 'GetAllCluster']);
    }

    public function getcluster(){
        $data = RegionalCluster::all();
        return response()->json(['data'=>$data]);
    }

    public function clusterstatus(request $request){
        $data = RegionalCluster::find($request->id);
        if($data->cluster_status=='Enable'){
            $data->cluster_status = 'Disable';
        }else{
            $data->cluster_status = 'Enable';
        }
        $data->save();
        return response()->json(['success' => true , 'message'=>'Cluster status successfully updated', 'reload'=> 'GetAllCluster']);  
    }

    public function addregion(request $request){

        $data = new Region();
        $data->cluster_id = $request->cluster_id;
        $data->region_name = $request->region_name;
        $data->save();

        return response()->json(['success' => true, 'message' => 'Region status successfully added', 'reload' => 'GetAllCluster']);  
    }

    public function getregionbycluster(request $request){
        $data = Region::where('cluster_id',$request->id)->get();
        return response()->json(['data' => $data]);
    }

    public function addcity(request $request){
        $data = new City();
        $data->region_id = $request->region_id;
        $data->city_name = $request->city_name;
        $data->save();
        return response()->json(['success' => true, 'message' => 'City status successfully added']);
    }

}
