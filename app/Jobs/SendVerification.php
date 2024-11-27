<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCode;

class SendVerification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    private string $code;
    private string $email;
    public function __construct(string $email, string $code)
    {
        $this->code = $code;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new VerificationCode($this->code));
    }
}
