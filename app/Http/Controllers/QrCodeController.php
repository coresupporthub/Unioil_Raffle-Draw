<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
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
use Illuminate\Http\JsonResponse;

class QrCodeController extends Controller
{
    public function generate(Request $req): JsonResponse
    {
        $latestQueue = QueueingStatusModel::latest()->first();
        $queue = new QueueingStatusModel();

        if ($latestQueue) {
            $queue->queue_number = $latestQueue->queue_number + 1;
        } else {
            $queue->queue_number = 1;
        }

        $queue->items = 0;
        $queue->total_items = $req->numberofqr;
        $queue->status = 'inprogress';
        $queue->entry_type = $req->qrtype;
        $queue->type = 'QR Generation';
        $queue->save();

        for ($i = 0; $i < $req->numberofqr; $i++) {
            GenerateQr::dispatch($req->qrtype);
        }

        $response = ['success' => true];
        $request = [
            'user_agent' => $req->userAgent(),
            'page_route' => $req->headers->get('referer'),
            'api_path' => $req->path(),
            'method' => $req->method(),
            'session_id' => $req->session()->getId(),
        ];
        Tools::Logger($request, $req->all(), ['Generate QR Code', 'QR Code generation is successfully in progress'], $response);
        return response()->json($response);
    }

    public function getqrcodegenerated(Request $request): JsonResponse
    {
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search')['value'];


        $query = QrCode::select('code', 'entry_type', 'status', 'export_status', 'qr_id');


        if (!empty($search)) {
            $query->where('code', 'like', "%$search%")
                ->orWhere('entry_type', 'like', "%$search%")
                ->orWhere('status', 'like', "%$search%");
        }

        $totalRecords = QrCode::count();


        $filteredRecords = $query->count();


        $data = $query->skip($start)->take($length)->get();


        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function queueProgress(Request $req): JsonResponse
    {
        $queue = QueueingStatusModel::all();
        $queueWithExportData = []; // Array to hold data

        foreach ($queue as $q) {
            $export = ExportFilesModel::where('queue_id', $q->queue_id)->first();

            $queueItem = [
                'queue' => $q,
                'export' => $export ? $this->getExportData($export) : null, // Add export data
            ];

            $queueWithExportData[] = $queueItem;
        }

        return response()->json(['queue' => $queueWithExportData]);
    }

    private function getExportData($export): array
    {
        $filePath = storage_path('app/pdf_files/' . $export->file_name);

        if (!file_exists($filePath)) {
            return [
                'exp_id' => $export->exp_id,
                'file_name' => $export->file_name,
                'base64File' => null,
            ];
        }

        $fileContent = file_get_contents($filePath);

        if ($fileContent === false) {
            return [
                'exp_id' => $export->exp_id,
                'file_name' => $export->file_name,
                'base64File' => null,
            ];
        }

        $base64Encoded = base64_encode($fileContent);
        $mimeType = mime_content_type($filePath);

        return [
            'exp_id' => $export->exp_id,
            'file_name' => $export->file_name,
            'queue_id' => $export->queue_id,
            'created_at' => $export->created_at,
            'updated_at' => $export->updated_at,
            'base64File' => 'data:' . $mimeType . ';base64,' . $base64Encoded,
        ];
    }

    public function exportQR(Request $req): Response
    {
        $latestQueue = QueueingStatusModel::latest()->first();
        $queue = new QueueingStatusModel();

        $limit = Magic::MAX_QR_PER_PAGE * $req->page_number;

        $checkQRCodes = QrCode::where('export_status', 'none')->where('status', 'unused')->where('entry_type', $req->qrtype)->count();

        if ($limit > $checkQRCodes) {
            return response()->json(['success' => false, 'message' => 'The QR Codes is not enough for the pages'], 403);
        }

        $qrCodes = QrCode::where('export_status', 'none')->where('status', 'unused')->where('entry_type', $req->qrtype)->take($limit)->select('image', 'qr_id')->get();

        if ($qrCodes->count() < Magic::MINIMUM_COUNT_FOR_EXPORT) {
            return response()->json(['success' => false, 'message' => 'No Unexported qr code images are available for export! Please add atleast 3 codes'], 404);
        }

        if ($latestQueue) {
            $queue->queue_number = $latestQueue->queue_number + 1;
        } else {
            $queue->queue_number = 1;
        }

        $queue->items = 0;
        $queue->total_items = $req->page_number;
        $queue->status = 'inprogress';
        $queue->entry_type = $req->qrtype;
        $queue->type = 'PDF Export';
        $queue->save();


        $qrCodes->transform(function ($qrCode) {
            $imagePath = 'app/qr-codes/' . $qrCode->image;
            if (file_exists($imagePath)) {
                $qrCode->image_base64 = 'data:image/png;base64,' . base64_encode($imagePath);
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
        if (!$checkExport) {
            $fileName = "qr_codes_export_1.pdf";
        } else {
            $inc = $checkExport->exp_id + 1;
            $fileName = "qr_codes_export_$inc.pdf";
        }
        $export->file_name = $fileName;
        $export->queue_id = $queue->queue_id;
        $export->save();

        $pdf = Pdf::loadView('Admin.pdf.export_qr', ['qrCodeChunkBy24' => $chunkedQrCodesArray, 'file_title' => $fileName, 'entry' => $req->qrtype]);

        foreach ($chunkedQrCodes as $qrCodesC) {
            foreach ($qrCodesC as $qrCodes) {
                foreach ($qrCodes as $qr) {
                    $qr = QrCode::where('qr_id', $qr['qr_id'])->first();

                    $qr->update([
                        'export_status' => 'exported'
                    ]);
                }
            }
        }



        $pdfFilePath = storage_path("app/pdf_files/$fileName");

        if (!file_exists(storage_path('app/pdf_files'))) {
            mkdir(storage_path('app/pdf_files'), 0777, true);

            chown(storage_path('app/pdf_files'), 'www-data');
            chgrp(storage_path('app/pdf_files'), 'www-data');
        }

        $pdf->save($pdfFilePath);
        $request = [
            'user_agent' => $req->userAgent(),
            'page_route' => $req->headers->get('referer'),
            'api_path' => $req->path(),
            'method' => $req->method(),
            'session_id' => $req->session()->getId(),
        ];
        Tools::Logger($request, $req->all(), ['Export QR Code', 'Successfully Exported QR Coupons in the PDF File'], ['open_pdf_file' => $fileName]);

        return $pdf->stream('qr_codes.pdf');
    }

    public function filterqr(Request $request): JsonResponse
    {
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search')['value'];

        $query = QrCode::where('entry_type', $request->filter)->select('code', 'entry_type', 'status', 'export_status', 'qr_id');


        if (!empty($search)) {
            $query->where('code', 'like', "%$search%")
                ->orWhere('entry_type', 'like', "%$search%")
                ->orWhere('status', 'like', "%$search%");
        }

        $totalRecords = QrCode::count();


        $filteredRecords = $query->count();

        $data = $query->skip($start)->take($length)->get();

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function viewqrdetails(Request $req): JsonResponse
    {
        $customer = Customers::where('qr_id', $req->id)->first();


        $qrCode = QrCode::where('qr_id', $req->id)->first();

        $imagePath = 'app/qr-codes/' . $qrCode->image;

        if (file_exists($imagePath)) {
            $qrCode->image_base64 = 'data:image/png;base64,' . base64_encode($imagePath);
        } else {
            $qrCode->image_base64 = null;
        }


        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'No customer found', 'qr' => $qrCode]);
        }

        $product = ProductList::where('product_id', $customer->product_purchased)->first();

        $entries = RaffleEntries::where('qr_id', $req->id)->join('retail_store', 'retail_store.rto_code', '=', 'raffle_entries.retail_store_code');
        if ($product->entries == 1) {
            $entry = $entries->first();
            $entry_type = 'Single Entry';
        } else {
            $entry = $entries->get();
            $entry_type = 'Double Entry';
        }

        return response()->json(['success' => true, 'entry_type' => $entry_type, 'customer' => $customer, 'entries' => $entry, 'product' => $product, 'qr' => $qrCode]);
    }


    public function checkexportnum(Request $req): JsonResponse
    {

        if ($req->filter == Magic::QR_ENTRY_SINGLE) {
            $qrCode = QrCode::where('export_status', Magic::EXPORT_FALSE)->where('status', Magic::QR_UNUSED)->where("entry_type", Magic::QR_ENTRY_SINGLE)->count();
        } else {
            $qrCode = QrCode::where('export_status', Magic::EXPORT_FALSE)->where('status', Magic::QR_UNUSED)->where("entry_type", Magic::QR_ENTRY_DOUBLE)->count();
        }

        if ($qrCode == 0) {
            return response()->json(['page' => 0]);
        }

        if ($qrCode < Magic::MAX_QR_PER_PAGE && $qrCode < Magic::MINIMUM_COUNT_FOR_EXPORT) {
            return response()->json(['page' => 1]);
        }

        $page = floor($qrCode / Magic::MAX_QR_PER_PAGE);

        return response()->json(['page' => $page]);
    }
}
