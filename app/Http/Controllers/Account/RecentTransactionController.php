<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerTransactionCollection;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Account\RecentTransactionService;
use Illuminate\Http\Request;

class RecentTransactionController extends Controller
{

    public function __construct(private readonly RecentTransactionService $recentService){}

    public function __invoke()
    {
        $recentTransactions = $this->recentService->getTransactions();

        return CustomerTransactionCollection::make($recentTransactions);
    }
}
