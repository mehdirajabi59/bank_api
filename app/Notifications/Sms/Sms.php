<?php

namespace App\Notifications\Sms;

use App\Notifications\Sms\Contracts\SmsInterface;

class Sms
{
    /**
     * Create a new Sms instance.
     *
     * @param SmsInterface $driver The SMS driver implementation.
     */
    public function __construct(private SmsInterface $driver){}

    /**
     * Send an SMS.
     *
     * @param string $phoneNumber The recipient's phone number.
     * @param string $text The text message to send.
     * @return mixed The result of the SMS sending operation.
     */
    public function send(string $phoneNumber, string $text)
    {
        return $this->driver->send($phoneNumber, $text);
    }
}
