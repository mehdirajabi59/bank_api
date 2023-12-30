<?php

namespace Database\Seeders;

use App\Models\BankFee;
use Illuminate\Database\Seeder;

class BankFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BankFee::factory(10)->create();
    }
}
