<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/transfer-money', [\App\Http\Controllers\Account\TransferController::class, '__invoke'])
    ->name('transfer-money')
    ->middleware(\App\Http\Middleware\CorrectNumberMiddleware::class .':source_card_number,dest_card_number,amount');

Route::get('recently-transactions', [\App\Http\Controllers\Account\RecentTransactionController::class, '__invoke']);

