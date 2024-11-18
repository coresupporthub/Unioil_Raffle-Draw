<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\GenerateQr;
use App\Models\QrCode;
use App\Models\QueueingStatusModel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ExportFilesModel;
use App\Models\Customers;
use App\Models\ProductList;
use App\Models\RaffleEntries;
use Illuminate\Support\Facades\Storage;

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
        $queue->entry_type = $req->qrtype;
        $queue->type = 'QR Generation';
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

    public function queueProgress(Request $req){
        $queue = QueueingStatusModel::all();
        foreach($queue as $q){
            $export = ExportFilesModel::where('queue_id', $q->queue_id)->first();

            if($export){
                $q->export = $export;
            }else{
                $q->export = null;
            }
        }
        return response()->json(['queue'=> $queue]);
    }

    public function exportQR(Request $req){
        $latestQueue = QueueingStatusModel::latest()->first();
        $queue = new QueueingStatusModel();

        $limit = 24 * $req->page_number;

        $qrCodes = QrCode::where('export_status', 'none')->where('status', 'unused')->take($limit)->select('image', 'qr_id')->get();

        if(count($qrCodes) < 3){
            return response()->json(['success'=> false, 'message'=> 'No Unexported qr code images are available for export! Please add atleast 3 codes'], 404);
        }

        if($latestQueue){
            $queue->queue_number = $latestQueue->queue_number + 1;
        }else{
            $queue->queue_number = 1;
        }

        $queue->items = 0;
        $queue->total_items = $req->page_number;
        $queue->status = 'inprogress';
        $queue->entry_type = $req->qrtype;
        $queue->type = 'PDF Export';
        $queue->save();


        $qrCodes->transform(function ($qrCode) {
            $imagePath = storage_path('app/qr-codes/' . $qrCode->image); // Adjust the path as needed
            if (file_exists($imagePath)) {
                $qrCode->image_base64 = 'data:image/png;base64,' . base64_encode(file_get_contents($imagePath));
            } else {
                $qrCode->image_base64 = null; // Handle missing image case
            }
            return $qrCode;
        });

        $chunkedQrCodes = $qrCodes->chunk(24)->map(function ($chunk) {
            return $chunk->chunk(3);
        });

        $chunkedQrCodesArray = $chunkedQrCodes->toArray();

        $checkExport = ExportFilesModel::latest()->first();
        $export = new ExportFilesModel();
        if(!$checkExport){
            $fileName = "qr_codes_export_1.pdf";
        }else{
            $inc = $checkExport->exp_id + 1;
            $fileName = "qr_codes_export_$inc.pdf";
        }
        $export->file_name = $fileName;
        $export->queue_id = $queue->queue_id;
        $export->save();

        $pdf = Pdf::loadView('Admin.pdf.export_qr', ['qrCodeChunkBy24'=> $chunkedQrCodesArray, 'file_title'=> $fileName]);

        foreach($chunkedQrCodes as $qrCodesC){
            foreach($qrCodesC as $qrCodes){
                foreach($qrCodes as $qr){
                    $qr = QrCode::where('qr_id', $qr['qr_id'])->first();

                    $qr->update([
                        'export_status'=> 'exported'
                    ]);
                }
            }
        }



        $pdfFilePath = storage_path("app/pdf_files/$fileName");

        if (!file_exists(storage_path('app/pdf_files'))) {
            mkdir(storage_path('app/pdf_files'), 0777, true);
        }

        $pdf->save($pdfFilePath);


        return $pdf->stream('qr_codes.pdf');

    }

    public function filterqr(Request $req){
        $qr = QrCode::where('entry_type', $req->filter)->get();

        return response()->json(['success'=> true, 'data'=> $qr]);
    }

    public function viewqrdetails(Request $req){
        $customer = Customers::where('qr_id', $req->id)->first();
        if(!$customer){
            return response()->json(['success'=> false, 'message'=> 'No customer found']);
        }

        $product = ProductList::where('product_id', $customer->product_purchased)->first();

        $entries = RaffleEntries::where('qr_id', $req->id);
        if($product->entries == 1){
            $entry = $entries->first();
        }else{
            $entry = $entries->get();
        }

        $qrCode = QrCode::where('qr_id', $req->id)->first();

        return response()->json(['success'=> true, 'customer'=> $customer, 'entries'=> $entry, 'product'=> $product, 'qr'=> $qrCode]);
    }
}
