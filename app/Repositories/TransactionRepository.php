<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{
    const CUSTOMER_COUNT = 3;

    public function getRecentUser()
    {
        return Transaction::query()
            ->with('accountCard.account:id,user_id')
            ->select('id', 'account_card_id')
            ->credit()
            ->where('created_at', '>=', now()->subMinutes(10))
            ->orderByDesc('amount')
            ->distinct('user_id')
            ->take(self::CUSTOMER_COUNT)
            ->get()
            ->pluck('accountCard.account.user_id')
            ->unique();
    }
}
