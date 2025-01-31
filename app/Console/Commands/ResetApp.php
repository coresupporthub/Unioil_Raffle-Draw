<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class ResetApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset The Whole App';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $eventImages = storage_path('app/event_images');
        $qrCodes = storage_path('app/public/qr-codes');
        $pdf_files = storage_path('app/public/pdf_files');

        $this->RemoveDirectory($eventImages);
        $this->RemoveDirectory($qrCodes);
        $this->RemoveDirectory($pdf_files);

        $this->info('Running migrations and seeding the database...');
        Artisan::call('migrate:fresh', ['--seed' => true]);

        $this->info(Artisan::output());

        Artisan::call('optimize');
        $this->info(Artisan::output());
    }

    private function RemoveDirectory(string $directory): void
    {

        if (File::exists($directory)) {
            File::deleteDirectory($directory);
            $this->info("Directory '$directory' has been deleted.");
        }else{
            $this->warn("Directory '$directory' does not exist.");
        }

    }
}
