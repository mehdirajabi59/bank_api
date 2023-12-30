<?php

namespace App\Notifications\Sms\Operators;

use App\Notifications\Sms\Contracts\SmsInterface;
use Ghasedak\GhasedakApi;

class Ghasedak implements SmsInterface
{

    public function send(string $phoneNumber, string $text)
    {
        $api = new GhasedakApi(config('ghasedak.apiKey'));
        $api->SendSimple(
            $phoneNumber,  // receptor
            $text, // message
            "300002525"    // choose a line number from your account
        );
    }
}
