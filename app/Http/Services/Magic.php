<?php
namespace App\Http\Services;
class Magic{
    const MAX_LOGIN_ATTEMPT = 5;
    const MAX_VERIFY_RESEND = 2;
    const MAX_VERIFICATION_ATTEMPT = 5;

    const RAFFLE_ENTRY_SINGLE = 'single';
    const RAFFLE_ENTRY_DOUBLE = 'double';

    const ACTIVE_EVENT = 'Active';
    const INACTIVE_EVENT = 'Inactive';

    const QUEUE_QR = 'QR Generation';
    const QUEUE_PDF = 'PDF Export';

    const EXPORT_TRUE = 'exported';
    const EXPORT_FALSE = 'none';

    const QR_USED = 'used';
    const QR_UNUSED = 'unused';

    const MAX_QR_PER_PAGE = 36;
    const MINIMUM_COUNT_FOR_EXPORT = 3;
}
