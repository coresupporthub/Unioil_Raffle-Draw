<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customers;
use App\Jobs\SendEntryCoupon;
use App\Models\QrCode;
use App\Http\Services\Tools;
use App\Models\ProductList;
use App\Http\Services\Magic;
use App\Models\RetailStore;

class CustomerRegistration extends Controller
{
    public function register(Request $req){
        $qrCode = QrCode::where('code', $req->qr_code)->where('qr_id', $req->unique_identifier)->first();

        if(!$qrCode){
            return response()->json(['success'=> false, 'message'=> 'QR Code Credentials is not found in the db']);
        }

        $qrStatus = QrCode::where('code', $req->qr_code)->where('qr_id', $req->unique_identifier)->where('status', 'used')->first();

        if($qrStatus){
            return response()->json(['success'=> false, 'message'=> 'QR Code is not available anymore']);
        }

        $retailStore = RetailStore::where('store_code', $req->store_code)->first();

        if(!$retailStore){
            return response()->json(['success'=> false, 'message'=> 'Retail Store Code is invalid please confirm the code to the store owner']);
        }

        $customer = new Customers();

        $customer->full_name = $req->fullname;
        $customer->age = $req->age;
        $customer->region = $req->region;
        $customer->province = $req->province;
        $customer->city = $req->city;
        $customer->brgy = $req->baranggay;
        $customer->street = $req->street;
        $customer->mobile_number = $req->mobile_number;
        $customer->email = $req->email_address;
        $customer->product_purchased = $req->product;
        $customer->save();

        $productEntry = ProductList::where('product_id', $req->product)->first();

        if($productEntry->entries == 1){
            $code = Tools::CreateEntries($customer, $req);
            SendEntryCoupon::dispatch(Magic::RAFFLE_ENTRY_SINGLE, $code, $req->email_address);
        }else{
            $code1 = Tools::CreateEntries($customer, $req);
            $code2 = Tools::CreateEntries($customer, $req);
            SendEntryCoupon::dispatch(Magic::RAFFLE_ENTRY_DOUBLE, [$code1, $code2], $req->email_address);
        }

        $qrCode->update([
            'status' => 'used'
        ]);

        return response()->json(['success'=> true, 'customer_id'=> $customer->customer_id, 'entry'=> $productEntry->entries]);
    }
}
