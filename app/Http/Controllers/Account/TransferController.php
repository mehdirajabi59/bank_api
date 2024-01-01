<?php

namespace App\Http\Controllers\Account;

use App\Events\TransferSucceed;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransferRequest;
use App\Services\Account\TransferService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransferController extends Controller
{

    public function __invoke(TransferRequest $request)
    {
        // Retrieve validated data from the request
        $validatedData = $request->validated();

        // Create a TransferService instance with validated data
        $transferService = app()->make(TransferService::class, [
            'sourceCard' => $validatedData['source_card_number'],
            'destCard' => $validatedData['dest_card_number'],
            'amount' => $validatedData['amount']
        ]);

        // Attempt to perform the transfer using the TransferService
        if (! $transferService->transfer()) {
            return response([
                'message' => __('transfer.race-condition')
            ], Response::HTTP_CONFLICT);
        }

        // Fire the TransferSucceed event to handle successful transfers
        TransferSucceed::dispatch(
            $transferService->sourceCard,
            $transferService->destCard,
            $transferService->getSourceAmount(),
            $transferService->getDestAmount()
        );

        return response([
            'message' => __('transfer.successful')
        ]);

    }
}
