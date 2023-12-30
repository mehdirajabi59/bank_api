<?php

namespace Database\Factories;

use App\Enums\TransactionType;
use App\Models\AccountCard;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class TransactionFactory extends Factory
{
    private $cards;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'account_card_id' => $this->getCardId(),
            'amount' => $this->faker->numberBetween(1000, 50000000),
            'transaction_type' => $this->faker->randomElement(\App\Enums\TransactionType::cases())
        ];
    }

    private function getCardId()
    {
        if (empty($this->cards)) {
            $this->cards = AccountCard::query()->get()->toArray();
        }

        return Arr::random($this->cards)['id'];
    }
}
