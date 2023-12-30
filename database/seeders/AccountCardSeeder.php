<?php

namespace Database\Seeders;

use App\Models\AccountCard;
use Illuminate\Database\Seeder;

class AccountCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccountCard::factory(10)->create();
    }
}
