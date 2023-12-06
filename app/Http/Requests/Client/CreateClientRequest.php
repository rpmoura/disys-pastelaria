<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\RequestAbstract;
use Illuminate\Validation\Rule;

class CreateClientRequest extends RequestAbstract
{
    public function rules(): array
    {
        return [
            'name'         => ['required', 'string'],
            'email'        => ['required', 'email:rfc,spoof,filter,strict', Rule::unique('clients', 'email')],
            'phone'        => ['required', 'string'],
            'birth_date'   => ['required', 'date:Y-m-d'],
            'address'      => ['required', 'string', 'max:255'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'add_on'       => ['nullable', 'string', 'max:255'],
            'postcode'     => ['required', 'string', 'max:8'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge($this->input('client', []));
    }
}
