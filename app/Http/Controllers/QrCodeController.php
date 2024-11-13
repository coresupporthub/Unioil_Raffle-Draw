<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\GenerateQr;


class QrCodeController extends Controller
{
    public function generate(Request $req)
    {

        for($i = 0; $i < $req->numberofqr; $i++){
            GenerateQr::dispatch($req->qrtype);
        }


        return response()->json(['success' => true]);
    }
}
