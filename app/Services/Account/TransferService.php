<?php

namespace App\Services\Account;

use App\Enums\TransferConfig;
use App\Models\AccountCard;
use App\Repositories\TransferRepo;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransferService
{
    /** @var AccountCard The source card for the transfer. */
    public AccountCard $sourceCard;

    /** @var AccountCard The destination card for the transfer. */
    public AccountCard $destCard;

    /** @var int The amount to be transferred. */
    private int $amount;

    /**
     * Create a new TransferService instance.
     *
     * @param int $sourceCardNumber The source card number.
     * @param int $destCardNumber The destination card number.
     * @param int $amount The amount to be transferred.
     */
    public function __construct(int $sourceCard, int $destCard, int $amount)
    {
        $this->sourceCard   = TransferRepo::getCard($sourceCard);
        $this->destCard     = TransferRepo::getCard($destCard);
        $this->amount = $amount;
    }

    /**
     * Get the total amount to be deducted from the source card (including fees).
     *
     * @return int
     */
    public function getSourceAmount(): int
    {
        return ($this->amount + TransferConfig::Fee->value);
    }

    /**
     * Get the amount to be transferred to the destination card.
     *
     * @return int
     */
    public function getDestAmount(): int
    {
        return $this->amount;
    }

    /**
     * Perform the transfer transaction.
     *
     * @return bool True if the transfer was successful, false otherwise.
     */
    public function transfer(): bool
    {
        DB::beginTransaction();

        try{
            $this->doTransfer();

            DB::commit();
            return true;
        }catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    /**
     * Perform the actual transfer logic.
     *
     * @throws ValidationException
     */
    private function doTransfer(): void
    {
        if (! $this->hasSufficientMoney()) {
            throw ValidationException::withMessages([
                'message' => __('transfer.not-sufficient-money')
            ]);
        }

        TransferRepo::doTransferAmount(
            source:         $this->sourceCard,
            dest:           $this->destCard,
            destAmount:     $this->getDestAmount(),
            amountWithFee:  $this->getSourceAmount()
        );
    }

    /**
     * Check if the source card has sufficient balance for the transfer.
     *
     * @return bool
     */
    public function hasSufficientMoney(): bool
    {
        return TransferRepo::getBalance($this->sourceCard->card_number) >= $this->getSourceAmount();
    }

}
