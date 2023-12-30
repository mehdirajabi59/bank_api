<?php

namespace App\Repositories;

use App\Enums\TransactionType;
use App\Models\AccountCard;

class TransferRepo
{

    public static function getCard($cardNumber): AccountCard
    {
        return AccountCard::query()
            ->select('card_number', 'id', 'account_id')
            ->where('card_number', $cardNumber)
            ->first();
    }

    public static function getBalance(int $cardNumber): int
    {
        $card = AccountCard::query()
                ->select('id', 'account_id')
                ->with('account:balance,id')
                ->where('card_number', $cardNumber)
                ->first();

        $card->account()->lockForUpdate();

        return $card->account->balance;

    }
    public static function doTransferAmount($source, $dest, int $destAmount, int $amountWithFee): void
    {

        $source->transactions()->create([
            'amount' => $amountWithFee,
            'transaction_type' => TransactionType::Debit
        ]);

        $dest->transactions()->create([
            'amount' => $destAmount,
            'transaction_type' => TransactionType::Credit
        ]);

        $source->account->decrement('balance', $amountWithFee);
        $dest->account->increment('balance', $destAmount);
    }
}
