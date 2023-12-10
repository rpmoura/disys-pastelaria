<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\RequestAbstract;
use App\Rules\ImageBase64;

class EditProductRequest extends RequestAbstract
{
    public function rules(): array
    {
        return [
            'name'  => ['sometimes', 'string'],
            'price' => ['sometimes', 'numeric'],
            'photo' => ['sometimes', 'string', new ImageBase64()],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge($this->input('product', []));
    }
}
