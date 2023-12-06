<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\RequestAbstract;
use Illuminate\Validation\Rule;

class EditClientRequest extends RequestAbstract
{
    public function rules(): array
    {
        return [
            'name'         => ['sometimes', 'string'],
            'email'        => ['sometimes', 'email:rfc,spoof,filter,strict', Rule::unique('clients', 'email')->ignore($this->uuid, 'uuid')],
            'phone'        => ['sometimes', 'string'],
            'birth_date'   => ['sometimes', 'date:Y-m-d'],
            'address'      => ['sometimes', 'string', 'max:255'],
            'neighborhood' => ['sometimes', 'string', 'max:255'],
            'add_on'       => ['nullable', 'string', 'max:255'],
            'postcode'     => ['sometimes', 'string', 'max:8'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge($this->input('client', []));
    }
}
