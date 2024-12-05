<?php

namespace App\Http\Controllers;

use App\Http\Services\Magic;
use Illuminate\Http\Request;
use App\Models\QrCode;
use App\Models\QueueingStatusModel;
use App\Models\ExportFilesModel;
use App\Models\Customers;
use App\Models\ProductList;
use App\Models\RaffleEntries;
use App\Http\Services\Tools;
use App\Jobs\ExportQrCoupon;
use Illuminate\Http\JsonResponse;
use App\Jobs\PackQueue;
use Symfony\Component\HttpFoundation\Response;

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

        PackQueue::dispatch((int) $req->numberofqr, $req->qrtype);

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

        $start = $req->input('start', 0);
        $length = $req->input('length', 10);
        $search = $req->input('search')['value'];

        $query = QueueingStatusModel::select('queue_number', 'total_items', 'items', 'status', 'entry_type', 'type', 'queue_id');

        if (!empty($search)) {
            $query->where('queue_number', 'like', "%$search%")
                ->orWhere('type', 'like', "%$search%")
                ->orWhere('status', 'like', "%$search%")
                ->orWhere('items', 'like', "%$search%")
                ->orWhere('total_items', 'like', "%$search%");
        }

        $totalRecords = QueueingStatusModel::count();
        $filteredRecords = $query->count();

        $queueWithExportData = [];

        $data = $query->skip($start)->take($length)->get();

        foreach ($data as $q) {
            $export = ExportFilesModel::where('queue_id', $q->queue_id)->first();

            $queueItem = [
                'queue' => $q,
                'file_name' => $export ? $export->file_name : null,
                'export' => $export ? $export->file_name : null,
            ];

            $queueWithExportData[] = $queueItem;
        }

        return response()->json([
            'draw' => intval($req->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $queueWithExportData
        ]);
    }

    public function zipdownload(string $path) : Response
    {
        $filePath = storage_path("app/pdf_files/$path");

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return response()->download($filePath, $path, [
            'Content-Type' => mime_content_type($filePath),
        ]);

    }


    public function exportQR(Request $req): JsonResponse
    {
        $limit = Magic::MAX_QR_PER_PAGE * $req->page_number;

        $checkQRCodes = QrCode::where('export_status', 'none')->where('status', 'unused')->where('entry_type', $req->qrtype)->count();

        if ($limit > $checkQRCodes) {
            return response()->json(['success' => false, 'message' => 'The QR Codes is not enough for the pages']);
        }

        $qrCodes = QrCode::where('export_status', 'none')->where('status', 'unused')->where('entry_type', $req->qrtype)->take($limit)->select('image', 'qr_id')->get();

        if ($qrCodes->count() < Magic::MINIMUM_COUNT_FOR_EXPORT) {
            return response()->json(['success' => false, 'message' => 'No Unexported qr code images are available for export! Please add atleast 3 codes']);
        }

        $latestQueue = QueueingStatusModel::latest()->first();
        $queue = new QueueingStatusModel();

        if ($latestQueue) {
            $queueNum = $latestQueue->queue_number + 1;
        } else {
            $queueNum  = 1;
        }

        $queue->queue_number = $queueNum;
        $queue->items = 0;
        $queue->total_items = $req->page_number;
        $queue->status = 'inprogress';
        $queue->entry_type = $req->qrtype;
        $queue->type = 'PDF Export';
        $queue->save();

        ExportQrCoupon::dispatch($req->qrtype, (int) $req->page_number, $queue->queue_id, (string) $queueNum);

        $request = [
            'user_agent' => $req->userAgent(),
            'page_route' => $req->headers->get('referer'),
            'api_path' => $req->path(),
            'method' => $req->method(),
            'session_id' => $req->session()->getId(),
        ];


        Tools::Logger($request, $req->all(), ['Export QR Code', 'Successfully Exported QR Coupons in the PDF File'], ['open_pdf_file' => 'Test']);

        return response()->json(['success'=> true, 'message'=> 'Export Qr Coupons is in progress please wait...']);
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

        if (!$qrCode) {
            return response()->json(['success' => false, 'message' => 'No QR Code Found']);
        }

        $imagePath = storage_path('app/qr-codes/' . $qrCode->image);

        if (file_exists($imagePath)) {
            $qrCode->image_base64 = 'data:image/png;base64,' . base64_encode((string)file_get_contents($imagePath));
        } else {
            $qrCode->image_base64 = null;
        }

        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'No customer found', 'qr' => $qrCode]);
        }

        $product = ProductList::where('product_id', $customer->product_purchased)->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'No Product Found']);
        }

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
