<?php

namespace App\Notifications;

class TransferNotif
{

    /**
     * Get the notification text for remaining balance.
     *
     * @param int $balance The remaining balance.
     * @return string The formatted notification text.
     */
    public static function getRemainText(int $balance): string
    {
        return __('transfer.notif-text', ['price' => $balance]);
    }
}
