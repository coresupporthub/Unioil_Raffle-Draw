<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\QueueingStatusModel;
use App\Models\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Services\Magic;
use App\Models\ExportFilesModel;
use ZipArchive;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ExportQrCoupon implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */

    private string $qrType;
    private int $pageNumber;
    private string $queue_id;
    private string $queue_number;
    public function __construct(string $qrType, int $pageNumber, string $queue_id, string $queue_number)
    {
        $this->qrType = $qrType;
        $this->pageNumber = $pageNumber;
        $this->queue_id = $queue_id;
        $this->queue_number = $queue_number;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $fileNames = [];

        $pageChunks = $this->divideIntoChunks($this->pageNumber, Magic::MAX_PAGE_PER_PDF);

        $pageNum = 1;

        foreach($pageChunks as $chunk){
            $fileNames[] = $this->GeneratePdfFile($chunk, $pageNum);
            $pageNum++;
        }

        $this->createZipFile($fileNames);

    }

    private function GeneratePdfFile(int $page, int $pageNum): string
    {
        $limit = $page * Magic::MAX_QR_PER_PAGE;

        $qrCodes = QrCode::where('export_status', 'none')->where('status', 'unused')->where('entry_type', $this->qrType)->take($limit)->select('image', 'qr_id')->get();

        $qrCodes->transform(function ($qrCode) {
            $qrCode->image_base64 = 'data:image/png;base64,' . base64_encode((string)file_get_contents(storage_path('app/qr-codes/' . $qrCode->image)));
            return $qrCode;
        });

        $chunkedQrCodes = $qrCodes->chunk(Magic::MAX_QR_PER_PAGE)->map(function ($chunk) {
            return $chunk->chunk(4);
        });

        $chunkedQrCodesArray = $chunkedQrCodes->toArray();

        $fileName = "qrCode{$pageNum}.pdf";

        $pdf = Pdf::loadView('Admin.pdf.export_qr', ['qrCodeChunkBy24' => $chunkedQrCodesArray, 'file_title' => $fileName, 'entry' => $this->qrType]);


        $pdfFilePath = storage_path("app/pdf_files/$fileName");

        if (!file_exists(storage_path('app/pdf_files'))) {
            mkdir(storage_path('app/pdf_files'), 0777, true);

            chown(storage_path('app/pdf_files'), 'www-data');
            chgrp(storage_path('app/pdf_files'), 'www-data');
        }

        $pdf->save($pdfFilePath);

        foreach ($chunkedQrCodes as $qrCodesC) {
            foreach ($qrCodesC as $qrCodes) {
                foreach ($qrCodes as $qr) {
                    $qr = QrCode::where('qr_id', $qr['qr_id'])->first();
                    if ($qr) {
                        $qr->update([
                            'export_status' => Magic::EXPORT_TRUE
                        ]);
                    }
                }
            }
        }

        $queueStatus = QueueingStatusModel::where('queue_id', $this->queue_id)->first();

        if($queueStatus){

            if($queueStatus->items + $page == $queueStatus->total_items){
                $queueStatus->update([
                    'items' => $queueStatus->items += $page,
                    'status' => 'done'
                ]);
            }else{
                $queueStatus->update([
                    'items' => $queueStatus->items += $page,
                ]);

            }

        }



        return $pdfFilePath;
    }

    /**
     * Divide a number into chunks.
     *
     * @return int[] Array of chunk sizes.
     */

    function divideIntoChunks(int $number, int $chunkSize): array
    {
        $chunks = [];

        while ($number > 0) {
            if ($number >= $chunkSize) {
                $chunks[] = $chunkSize;
                $number -= $chunkSize;
            } else {
                $chunks[] = $number;
                $number = 0;
            }
        }

        return $chunks;
    }

    /**
     * Create a ZIP file from an array of file paths.
     *
     * @param string[] $filePaths Array of file paths to include in the ZIP.
     */
    private function createZipFile(array $filePaths): void
    {
        $zipFileName = "qr_codes_export". $this->queue_number . ".zip";
        $zipFilePath = storage_path("app/pdf_files/$zipFileName");

        $zip = new ZipArchive();

        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($filePaths as $filePath) {
                $zip->addFile($filePath, basename($filePath));
            }
            $zip->close();
        }
        $directory = storage_path('app/pdf_files');

        if (file_exists($directory)) {
            $files = (array) glob($directory . '/*.pdf');

            foreach ($files as $file) {
                if (is_file((string) $file)) {
                    unlink((string) $file);
                }
            }
        }


        $export = new ExportFilesModel();
        $export->file_name = $zipFileName;
        $export->queue_id = $this->queue_id;
        $export->save();
    }
}
