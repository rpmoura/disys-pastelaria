<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\RequestAbstract;
use Illuminate\Validation\Rule;

class CreateOrderRequest extends RequestAbstract
{
    public function rules(): array
    {
        return [
            'client.uuid'     => ['required', 'string', Rule::exists('clients', 'uuid')],
            'products'        => ['required', 'array'],
            'products.*.uuid' => ['required', 'string', Rule::exists('products', 'uuid')],
        ];
    }

    public function attributes(): array
    {
        return [
            'client.uuid'     => 'client',
            'products.*.uuid' => 'product',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge($this->input('order', []));
    }
}
