<?php

namespace App\Notifications\Sms\Contracts;

interface SmsInterface
{
    public function send(string $phoneNumber, string $text);
}
