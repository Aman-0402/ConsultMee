<?php
declare(strict_types=1);

return [
    'from'      => env('MAIL_FROM',      'no-reply@consultmee.in'),
    'from_name' => env('MAIL_FROM_NAME', 'ConsultMee'),
    'reply_to'  => env('MAIL_REPLY_TO',  'info@consultmee.in'),
];
