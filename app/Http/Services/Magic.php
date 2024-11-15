<?php
namespace App\Http\Services;
class Magic{
    const MAX_LOGIN_ATTEMPT = 5;
    const MAX_VERIFY_RESEND = 2;

    const RAFFLE_ENTRY_SINGLE = 'single';
    const RAFFLE_ENTRY_DOUBLE = 'double';

    const ACTIVE_EVENT = 'Active';
    const INACTIVE_EVENT = 'Inactive';
}
