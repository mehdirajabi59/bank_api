<?php

namespace Tests\Feature;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecentlyTransactionTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * A basic feature test example.
     */
    public function test_recently_transactions_is_correct_response(): void
    {

        $response = $this->getJson(route('recently-transactions'));

        $response->assertOk()
        ->assertJsonStructure([
            'data' => ['*' => [
                'name',
                'mobile',
                'transactions' => ['*' => [
                    'amount',
                    'created_at'
                ]]
            ]]
        ]);

        $this->assertLessThanOrEqual(10, count($response->json('data')));

        foreach ($response->json('data') as $data) {
            foreach($data['transactions'] as $transaction) {
                $this->assertTrue(Carbon::createFromDate($transaction['created_at'])->greaterThan(now()->subMinutes(11)));
            }
        }
    }
}
