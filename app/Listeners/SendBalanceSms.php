<?php

namespace App\Listeners;

use App\Events\TransferSucceed;
use App\Notifications\Sms\Sms;
use \App\Facades\SMS as SMSFacade;
use App\Notifications\TransferNotif;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendBalanceSms implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Handle the event.
     */
    public function handle(TransferSucceed $event): void
    {
        //Send SMS to source card
        SMSFacade::send(
            $event->sourceCard->account->user->mobile,
            TransferNotif::getRemainText($event->sourceAmount)
        );
        //Send SMS to destination card
        SMSFacade::send(
            $event->destCard->account->user->mobile,
            TransferNotif::getRemainText($event->destAmount)
        );
    }
}
