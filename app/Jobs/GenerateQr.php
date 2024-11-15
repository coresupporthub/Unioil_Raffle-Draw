<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use App\Models\QrCode;
use App\Http\Services\Tools;
use App\Models\QueueingStatusModel;
use Illuminate\Support\Facades\Storage;

class GenerateQr implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */

    private $entry_type;
    public function __construct($entry_type)
    {
        $this->entry_type = $entry_type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $code = Tools::genCode();
        $check = QrCode::where('code', $code)->first();

        while ($check) {
            $code = Tools::genCode();
            $check = QrCode::where('code', $code)->first();
        }

        $fileName = "{$code}.png";

        $entry_type = $this->entry_type;
        $qrCodeModel = new QrCode();
        $qrCodeModel->code = $code;
        $qrCodeModel->entry_type = $entry_type;
        $qrCodeModel->status = 'unused';
        $qrCodeModel->image = $fileName;
        $qrCodeModel->export_status = 'none';
        $qrCodeModel->save();

        $urlConstruct = route('customer_registrations', ['code' => $code, 'uuid' => $qrCodeModel->qr_id]);


        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $urlConstruct,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            logoResizeToWidth: 100,
            logoPunchoutBackground: true,
            logoPath: public_path('/unioil_images/unioil.png'),
            labelText: $entry_type,
            labelFont: new OpenSans(20),
            labelAlignment: LabelAlignment::Center
        );

        $result = $builder->build();

        $qrCodePath = storage_path("app/qr-codes/{$fileName}");

        if (!file_exists(storage_path('app/qr-codes'))) {
            mkdir(storage_path('app/qr-codes'), 0777, true);
        }

        $result->saveToFile($qrCodePath);

        $q = QueueingStatusModel::where('status', 'inprogress')->first();

        if ($q) {
            if ($q->items + 1 != $q->total_items) {
                $q->update([
                    'items' => $q->items + 1,
                ]);
            } else {
                $q->update([
                    'items' => $q->items + 1,
                    'status' => 'done'
                ]);
            }
        }
    }
}
