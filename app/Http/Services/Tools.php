<?php
namespace App\Http\Services;

class Tools{

    public static function genCode($length = 15){
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

}
