<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\MyCustomEmail;

class EmailService
{
    public function sendEmail(string $to, string $subject, string $content)
    {
        Mail::mailer('intranet')->send(new MyCustomEmail($subject, $content));
    }
}
