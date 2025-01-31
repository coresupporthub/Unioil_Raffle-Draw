<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\QrCode;
use App\Http\Services\Magic;
use Illuminate\Support\Facades\File;
use App\Models\ExportFilesModel;
use App\Models\QueueingStatusModel;

class ResetExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset All Export To None';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info("Resetting Export");

        $qrCode = QrCode::where('export_status', Magic::EXPORT_TRUE)->get();


        foreach($qrCode as $code){
            $code->update([
                'export_status' => Magic::EXPORT_FALSE
            ]);
        }

        $this->info("Clear all QR Codes Status");

        $pdf_files = storage_path('app/public/pdf_files');

        if (File::exists($pdf_files)) {
            File::deleteDirectory($pdf_files);
            $this->info("Directory '$pdf_files' has been deleted.");
        }else{
            $this->warn("Directory '$pdf_files' does not exist.");
        }

        ExportFilesModel::truncate();
        $this->info("Truncate Export Files");
        $queue = QueueingStatusModel::where('type', 'PDF Export')->get();

        foreach($queue as $q){
            $q->delete();
        }
        $this->info("Clear all Queues");

        $this->info("Export Reset Done");
    }
}
