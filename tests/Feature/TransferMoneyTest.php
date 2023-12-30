<?php

namespace Tests\Feature;

use App\Enums\TransferConfig;
use App\Events\TransferSucceed;
use App\Listeners\SendBalanceSms;
use App\Models\AccountCard;
use App\Notifications\Sms\Contracts\SmsInterface;
use App\Notifications\Sms\Operators\Kavehnegar;
use App\Rules\IranianBankCardNumber;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TransferMoneyTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        app()->bind(IranianBankCardNumber::class, fn ()=> new IranianBankCardNumberMock());
        app()->bind(Kavehnegar::class, fn() => new MockKavehnegar());
    }

    /**
     * A basic feature test example.
     */
    public function test_transfer_total_balance_expect_balance_is_zero(): void
    {

        Event::fake();

        $amountTransfer = fake()->numberBetween(1000, 500000);

        $sourceCard = AccountCard::query()->inRandomOrder()->first();
        $sourceCard->account->update(['balance' => $amountTransfer + TransferConfig::Fee->value]);

        $destCard = AccountCard::query()->inRandomOrder()->where('account_id', '!=', $sourceCard->account_id)->first();
        $destCard->account->update(['balance' => 0]);

        $response = $this->postJson(route('transfer-money'), [
            'source_card_number' => $sourceCard->card_number,
            'dest_card_number' => $destCard->card_number,
            'amount' => $amountTransfer
        ]);

        $response->assertStatus(200);

        $sourceCard->refresh();
        $destCard->refresh();

        $this->assertEquals($sourceCard->account->balance, 0);
        $this->assertEquals($destCard->account->balance, $amountTransfer);

        Event::assertDispatched(TransferSucceed::class);
    }
}


class MockKavehnegar implements SmsInterface
{

    public function send(string $phoneNumber, string $text)
    {
        // TODO: Implement send() method.
    }
}

class IranianBankCardNumberMock implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure|\Closure $fail): void
    {

    }
}
