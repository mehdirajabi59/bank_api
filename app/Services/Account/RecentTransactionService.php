<?php

namespace App\Services\Account;

use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;

class RecentTransactionService
{
    public function __construct(private readonly UserRepository $userRepository, private readonly TransactionRepository $transactionRepository){}

    public function getTransactions(): Collection|array
    {
        $userIds = $this->transactionRepository->getRecentUser();

        return $this->userRepository->getUsersWithRecentTransactions($userIds);
    }
}
