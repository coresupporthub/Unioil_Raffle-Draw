<?php
namespace App\Http\Services;
use App\Models\RaffleEntries;
class Tools{

    public static function genCode($length = 15, $type = "alphanumeric"){
        switch($type){
            case 'numeric':
                $characters = '0123456789';
                break;
            default:
                $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                break;
        }

        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function CreateEntries($customer, $req){
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

        return $serialNumber;
    }
}
