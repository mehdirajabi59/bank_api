<?php

namespace App\Http\Requests;

use App\Enums\TransferConfig;
use App\Rules\IranianBankCardNumber;
use App\Rules\SufficientFundsRule;
use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'source_card_number'    => ['required', 'numeric', app(IranianBankCardNumber::class),'exists:account_cards,card_number'],
            'dest_card_number'      => ['required', 'numeric', app(IranianBankCardNumber::class), 'exists:account_cards,card_number'],
            'amount'                => ['required', 'numeric', 'min:'. TransferConfig::Min->value, 'max:'. TransferConfig::Max->value]
        ];
    }
}
