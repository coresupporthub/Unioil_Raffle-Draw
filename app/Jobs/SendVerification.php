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
    private $code;
    private $email;
    public function __construct($email, $code)
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
