<?php

namespace Database\Factories;

use App\Enums\TransferConfig;
use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BankFee>
 */
class BankFeeFactory extends Factory
{
    private $accounts;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price' => TransferConfig::Fee->value,
            'account_id' => $this->getAccountId()
        ];
    }

    private function getAccountId()
    {
        if (empty($this->accounts)) {
            $this->accounts = Account::query()->get()->toArray();
        }

        return Arr::random($this->accounts)['id'];
    }
}
