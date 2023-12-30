<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class AccountFactory extends Factory
{
    private $users;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->getUserId(),
            'account_number' => $this->faker->unique()->numberBetween(100000000, 9999999999),
        ];
    }
    private function getUserId()
    {
        if (empty($this->users)) {
            $this->users = User::query()->get()->toArray();
        }

        return Arr::random($this->users)['id'];
    }
}
