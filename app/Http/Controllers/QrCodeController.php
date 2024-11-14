<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\GenerateQr;
use App\Models\QrCode;
use App\Models\QueueingStatusModel;

class QrCodeController extends Controller
{
    public function generate(Request $req)
    {

        $latestQueue = QueueingStatusModel::latest()->first();
        $queue = new QueueingStatusModel();

        if($latestQueue){
            $queue->queue_number = $latestQueue->queue_number + 1;
        }else{
            $queue->queue_number = 1;
        }

        $queue->items = 0;
        $queue->total_items = $req->numberofqr;
        $queue->status = 'inprogress';
        $queue->save();

        for($i = 0; $i < $req->numberofqr; $i++){
            GenerateQr::dispatch($req->qrtype);
        }


        return response()->json(['success' => true]);
    }

    public function getqrcodegenerated()
    {
        $qrcodes = QrCode::all();

        return response()->json(['qrcodes' => $qrcodes]);
    }

    public function deletegeneratedqr(request $request){
        $qrcodes = QrCode::where('qr_id', $request->qr_id)->first();
        if($qrcodes){

            $filePath = public_path('qr-codes/'.$qrcodes->image); // Example file path

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $qrcodes->delete();
        }
        return response()->json(['success' => true]);
    }
}
