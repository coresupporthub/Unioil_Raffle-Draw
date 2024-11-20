<?php

namespace App\Http\Controllers;

use App\Http\Services\Magic;
use Illuminate\Http\Request;
use App\Jobs\GenerateQr;
use App\Models\QrCode;
use App\Models\QueueingStatusModel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ExportFilesModel;
use App\Models\Customers;
use App\Models\ProductList;
use App\Models\RaffleEntries;
use App\Http\Services\Tools;

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

        $response = ['success' => true];
        Tools::Logger($req, ['Generate QR Code', 'QR Code generation is successfully in progress'], $response);
        return response()->json($response);
    }

    public function getqrcodegenerated()
    {
        $qrcodes = QrCode::all();

        return response()->json(['qrcodes' => $qrcodes]);
    }

    public function queueProgress(Request $req)
    {
        $queue = QueueingStatusModel::all();

        foreach ($queue as $q) {
            $export = ExportFilesModel::where('queue_id', $q->queue_id)->first();

            if ($export) {
                $filePath = storage_path('app/pdf_files/' . $export->file_name);


                if (file_exists($filePath)) {
                    $fileContent = file_get_contents($filePath);
                    $base64Encoded = base64_encode($fileContent);
                    $mimeType = mime_content_type($filePath);

                    $dataUri = 'data:' . $mimeType . ';base64,' . $base64Encoded;
                    $export->base64File = $dataUri;
                } else {

                    $export->base64File = null;
                }

                $q->export = $export;
            } else {
                $q->export = null;
            }
        }

        return response()->json(['queue' => $queue]);
    }

    public function exportQR(Request $req){
        $latestQueue = QueueingStatusModel::latest()->first();
        $queue = new QueueingStatusModel();

        $limit = Magic::MAX_QR_PER_PAGE * $req->page_number;

        $checkQRCodes = QrCode::where('export_status', 'none')->where('status', 'unused')->get()->count();

        if($limit > $checkQRCodes){
            return response()->json(['success'=> false, 'message'=> 'The QR Codes is not enough for the pages'], 403);
        }

        $qrCodes = QrCode::where('export_status', 'none')->where('status', 'unused')->take($limit)->select('image', 'qr_id')->get();

        if($qrCodes->count() < Magic::MINIMUM_COUNT_FOR_EXPORT){
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
            $imagePath = storage_path('app/qr-codes/' . $qrCode->image);
            if (file_exists($imagePath)) {
                $qrCode->image_base64 = 'data:image/png;base64,' . base64_encode(file_get_contents($imagePath));
            } else {
                $qrCode->image_base64 = null;
            }
            return $qrCode;
        });

        $chunkedQrCodes = $qrCodes->chunk(Magic::MAX_QR_PER_PAGE)->map(function ($chunk) {
            return $chunk->chunk(4);
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

        $pdf = Pdf::loadView('Admin.pdf.export_qr', ['qrCodeChunkBy24'=> $chunkedQrCodesArray, 'file_title'=> $fileName, 'entry' => $req->qrtype]);

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

        Tools::Logger($req, ['Export QR Code', 'Successfully Exported QR Coupons in the PDF File'], ['open_pdf_file'=> $fileName]);

        return $pdf->stream('qr_codes.pdf');

    }

    public function filterqr(Request $req){
        $qr = QrCode::where('entry_type', $req->filter)->get();

        return response()->json(['success'=> true, 'data'=> $qr]);
    }

    public function viewqrdetails(Request $req){
        $customer = Customers::where('qr_id', $req->id)->first();


        $qrCode = QrCode::where('qr_id', $req->id)->first();

        $imagePath = storage_path('app/qr-codes/' . $qrCode->image);

        if (file_exists($imagePath)) {
            $qrCode->image_base64 = 'data:image/png;base64,' . base64_encode(file_get_contents($imagePath));
        } else {
            $qrCode->image_base64 = null;
        }


        if(!$customer){
            return response()->json(['success'=> false, 'message'=> 'No customer found', 'qr'=> $qrCode]);
        }

        $product = ProductList::where('product_id', $customer->product_purchased)->first();

        $entries = RaffleEntries::where('qr_id', $req->id)->join('retail_store', 'retail_store.rto_code', '=', 'raffle_entries.retail_store_code' );
        if($product->entries == 1){
            $entry = $entries->first();
            $entry_type = 'Single Entry';
        }else{
            $entry = $entries->get();
            $entry_type = 'Double Entry';
        }

        return response()->json(['success'=> true, 'entry_type'=> $entry_type, 'customer'=> $customer, 'entries'=> $entry, 'product'=> $product, 'qr'=> $qrCode]);
    }


    public function checkexportnum(Request $req){
        $qrCode = QrCode::where('export_status', Magic::EXPORT_FALSE)->where('status', Magic::QR_UNUSED)->get()->count();

        if($qrCode < Magic::MAX_QR_PER_PAGE && $qrCode < Magic::MINIMUM_COUNT_FOR_EXPORT){
            return response()->json(['page'=> 1]);
        }

        $page = floor($qrCode / Magic::MAX_QR_PER_PAGE);

        return response()->json(['page'=> $page]);
    }
}
