<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customers;
use App\Models\RaffleEntries;
use App\Models\QrCode;
use App\Http\Services\Tools;
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


        $serialNumber = Tools::genCode();

        $checkSerialNumber = RaffleEntries::where('serial_number', $serialNumber)->first();

        while($checkSerialNumber){
            $serialNumber = Tools::genCode();
            $checkSerialNumber = RaffleEntries::where('serial_number', $serialNumber)->first();
        }

        $entry = new RaffleEntries();

        $entry->customer_id = $customer->customer_id;
        $entry->serial_number = $serialNumber;
        $entry->qr_id = $req->unique_identifier;
        $entry->retail_store_code = $req->store_code;
        $entry->save();

        

        $qrCode->update([
            'status' => 'used'
        ]);

        return response()->json(['data'=> 'test']);
    }
}
