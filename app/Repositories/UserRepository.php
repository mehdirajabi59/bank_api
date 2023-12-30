<?php

namespace App\Repositories;


use App\Models\User;

class UserRepository
{
    public function getUsersWithRecentTransactions($userIds)
    {
        return User::query()
            ->select('id', 'name', 'mobile')
            ->with(['accounts.transactions' => fn($query) => $query->select('transactions.amount', 'transactions.created_at')->take(10)])
            ->whereIn('id', $userIds)
            ->get();
    }
}
