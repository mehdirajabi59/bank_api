<?php

namespace Tests\Feature;

use App\Enums\TransferConfig;
use App\Events\TransferSucceed;
use App\Exceptions\NoSufficientMoneyException;
use App\Models\AccountCard;
use App\Notifications\Sms\Operators\Kavehnegar;
use App\Rules\IranianBankCardNumber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TransferMoneyTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $mockIranianBankCardNumber = $this->mock(IranianBankCardNumber::class);
        $mockIranianBankCardNumber->allows('validate')->andReturnTrue();
        app()->instance(IranianBankCardNumber::class, $mockIranianBankCardNumber);

        $mockIranianBankCardNumber = $this->mock(Kavehnegar::class);
        $mockIranianBankCardNumber->allows('send')->andReturnTrue();
        app()->instance(Kavehnegar::class, $mockIranianBankCardNumber);
    }

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

        $response->assertOk()
            ->assertJson([
                'message' => __('transfer.successful')
            ]);

        $sourceCard->refresh();
        $destCard->refresh();

        $this->assertEquals(0, $sourceCard->account->balance);
        $this->assertEquals($destCard->account->balance, $amountTransfer);

        Event::assertDispatched(TransferSucceed::class);
    }

    public function test_transfer_money_with_persian_numbers(): void
    {

        Event::fake();

        $amountTransfer = fake()->numberBetween(1000, 500000);

        $sourceCard = AccountCard::factory()->hasAccount()->create();
        $sourceCard->account->update(['balance' => $amountTransfer + TransferConfig::Fee->value]);

        $destCard = AccountCard::query()->inRandomOrder()->where('account_id', '!=', $sourceCard->account_id)->first();
        $destCard->account->update(['balance' => 0]);

        $response = $this->postJson(route('transfer-money'), [
            'source_card_number' => $this->englishNumberToPersian($sourceCard->card_number),
            'dest_card_number' => $this->englishNumberToPersian($destCard->card_number),
            'amount' => $this->englishNumberToPersian($amountTransfer)
        ]);

        $response->assertOk()
            ->assertJson([
                'message' => __('transfer.successful')
            ]);

        $sourceCard->refresh();
        $destCard->refresh();

        $this->assertEquals(0, $sourceCard->account->balance);
        $this->assertEquals($destCard->account->balance, $amountTransfer);

        Event::assertDispatched(TransferSucceed::class);
    }

    public function test_transfer_fails_when_source_account_has_insufficient_funds(): void
    {
        Event::fake();
        $amountTransfer = fake()->numberBetween(1000, 500000);
        $sourceCard = AccountCard::factory()->hasAccount()->create();
        $sourceCard->account->update(['balance' => $amountTransfer]);

        $destCard = AccountCard::query()->inRandomOrder()->where('account_id', '!=', $sourceCard->account_id)->first();
        $destCard->account->update(['balance' => 0]);

        $response = $this->postJson(route('transfer-money'), [
            'source_card_number' => $sourceCard->card_number,
            'dest_card_number' => $destCard->card_number,
            'amount' => $amountTransfer
        ]);

        $response->assertBadRequest()
            ->assertJson([
                'message' => __('transfer.not-sufficient-money')
            ]);

        $sourceCard->refresh();
        $destCard->refresh();

        Event::assertNotDispatched(TransferSucceed::class);
    }

    private function englishNumberToPersian($enNumbers)
    {
        return str_replace([0, 1, 2, 3, 4, 5, 6, 7, 8, 9], ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'], $enNumbers);
    }
}
