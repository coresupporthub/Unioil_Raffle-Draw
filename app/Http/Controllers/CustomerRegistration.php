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
use App\Models\Event;

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

        $retailStore = RetailStore::where('rto_code', $req->store_code)->first();

        if(!$retailStore){
            return response()->json(['success'=> false, 'message'=> 'Retail Store Code is invalid please confirm the code to the store owner']);
        }

        $currentActiveEvent = Event::where('event_status', Magic::ACTIVE_EVENT)->first();

        if(!$currentActiveEvent){
            return response()->json(['success'=> false, 'message'=> 'There is no current promo event available for this entry']);
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
        $customer->qr_id = $qrCode->qr_id;
        $customer->product_purchased = $req->product;
        $customer->store_id = $retailStore->store_id;
        $customer->event_id = $currentActiveEvent->event_id;
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

    public function checkretailstore(Request $req){
        $store = RetailStore::where('rto_code', $req->rto_code)->first();

        if(!$store){
            return response()->json(['success'=> false, 'message'=> 'Retail code is invalid please ask the store owner for there code']);
        }

        return response()->json(['success'=> true, 'message'=> 'Verify Retail Code', 'store'=>$store]);
    }
}
