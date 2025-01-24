<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\ExportFilesModel;
use ZipArchive;
use Illuminate\Contracts\Queue\ShouldBeUnique;
class CompressFiles implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    /**
    * @var string[] Array of file paths to include in the ZIP.
    */

    private array $fileNames;
    private string $queue_number;
    private string $queue_id;
    private string $qrType;

    /**
     * Create a new job instance.
     * @param string[] $fileNames Array of file paths to include in the ZIP.
     */

    public function __construct(array $fileNames, string $queue_number, string $queue_id, string $qrType)
    {
        $this->fileNames = $fileNames;
        $this->queue_number = $queue_number;
        $this->queue_id = $queue_id;
        $this->qrType = $qrType;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $filePaths = $this->fileNames;
        $zipFileName = $this->qrType . "_qr_codes_export". $this->queue_number . ".zip";
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
