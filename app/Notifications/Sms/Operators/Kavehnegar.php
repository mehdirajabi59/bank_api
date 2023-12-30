<?php

namespace App\Notifications\Sms\Operators;

use App\Notifications\Sms\Contracts\SmsInterface;
use Kavenegar as KavehnegarService;

class Kavehnegar implements SmsInterface
{
    public function send(string $phoneNumber, string $text): void
    {
        try{
            $sender = "10004346";

            $receptor = [$phoneNumber];
            KavehnegarService::Send($sender,$receptor,$text);
        }
        catch(\Kavenegar\Exceptions\ApiException $e){
            // در صورتی که خروجی وب سرویس 200 نباشد این خطا رخ می دهد
            echo $e->errorMessage();
        }
        catch(\Kavenegar\Exceptions\HttpException $e){
            // در زمانی که مشکلی در برقرای ارتباط با وب سرویس وجود داشته باشد این خطا رخ می دهد
            echo $e->errorMessage();
        }
    }
}
