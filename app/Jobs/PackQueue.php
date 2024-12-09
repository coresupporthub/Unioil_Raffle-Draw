<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Jobs\GenerateQr;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class PackQueue implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    private int $numberQr;
    private string $qrtype;
    public function __construct(int $numberQr, string $qrtype)
    {
        $this->numberQr = $numberQr;
        $this->qrtype = $qrtype;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        for ($i = 0; $i < $this->numberQr; $i++) {
            GenerateQr::dispatch($this->qrtype);
        }
    }
}
