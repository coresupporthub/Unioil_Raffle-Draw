<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Mail\EntryCouponSingle;
use App\Mail\EntryCouponDouble;
use Illuminate\Support\Facades\Mail;
use App\Http\Services\Magic;

class SendEntryCoupon implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    private string $type;
    private string $code;
    private string $email;
    public function __construct(string $type, string $code, string $email)
    {
        $this->type = $type;
        $this->code = $code;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if($this->type == Magic::RAFFLE_ENTRY_SINGLE){
            Mail::to($this->email)->send(new EntryCouponSingle($this->code));
        }else{
            Mail::to($this->email)->send(new EntryCouponDouble($this->code[0], $this->code[1]));
        }
    }
}
