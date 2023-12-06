<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid'         => $this->uuid,
            'name'         => $this->name,
            'email'        => $this->email,
            'phone'        => $this->phone,
            'birth_date'   => $this->birth_date,
            'address'      => $this->address,
            'neighborhood' => $this->neighborhood,
            'add_on'       => $this->add_on,
            'postcode'     => $this->postcode,
            'created_at'   => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
