<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class AccountCardFactory extends Factory
{
    private $accounts;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'account_id' => $this->getAccountId(),
            'card_number' => $this->faker->unique()->numerify('621986106219####'),
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
