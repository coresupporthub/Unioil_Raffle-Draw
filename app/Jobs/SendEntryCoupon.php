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
     * The type of the coupon.
     */
    private string $type;

    /**
     * Array of coupon codes.
     *
     * @var array<string>
     */
    private array $code;

    /**
     * The recipient's email address.
     */
    private string $email;

    /**
     * Create a new job instance.
     *
     * @param string $type The type of the coupon.
     * @param array<string> $code Array of coupon codes.
     * @param string $email The recipient's email address.
     */
    public function __construct(string $type, array $code, string $email)
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
        if ($this->type === Magic::RAFFLE_ENTRY_SINGLE) {
            Mail::to($this->email)->send(new EntryCouponSingle($this->code[0]));
        } else {
            Mail::to($this->email)->send(new EntryCouponDouble($this->code[0], $this->code[1]));
        }
    }
}
