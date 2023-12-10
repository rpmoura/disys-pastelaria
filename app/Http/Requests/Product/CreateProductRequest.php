<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\RequestAbstract;
use App\Rules\ImageBase64;

class CreateProductRequest extends RequestAbstract
{
    public function rules(): array
    {
        return [
            'name'  => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'photo' => ['required', 'string', new ImageBase64()],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge($this->input('product', []));
    }
}
