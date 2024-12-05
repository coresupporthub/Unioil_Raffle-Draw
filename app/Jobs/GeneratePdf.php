<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Http\Services\Magic;
use App\Models\QueueingStatusModel;
use App\Models\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
class GeneratePdf implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */

    private int $page;
    private string $fileName;
    private string $qrType;
    private string $queue_id;
    public function __construct(int $page, string $fileName, string $qrType, string $queue_id)
    {
        $this->page = $page;
        $this->fileName = $fileName;
        $this->qrType = $qrType;
        $this->queue_id = $queue_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $page = $this->page;

        $fileName = $this->fileName;

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

    }
}
