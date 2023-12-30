<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerTransactionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->map(fn ($item) => [
            'name' => $item->name,
            'mobile' => $item->mobile,
            'transactions' => $item->accounts->map(fn ($a) =>
                $a->transactions->map(fn ($t) => [
                    'amount' => $t->amount,
                    'created_at' => $t->created_at
                ]))[0]
        ]);
    }
}
