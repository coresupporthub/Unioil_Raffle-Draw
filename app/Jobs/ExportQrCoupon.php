<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Http\Services\Magic;
use App\Jobs\GeneratePdf;
use App\Jobs\CompressFiles;
use Illuminate\Support\Facades\Log;
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
            $fileName = "{$this->qrType}_qrCode{$pageNum}.pdf";
            $fileNames[] = storage_path("app/pdf_files/$fileName");
            GeneratePdf::dispatch($chunk, $fileName, $this->qrType, $this->queue_id);
            $pageNum++;
        }

        Log::info('Files List', ['fileNames' => $fileNames]);
        CompressFiles::dispatch($fileNames, $this->queue_number, $this->queue_id, $this->qrType);
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


}
