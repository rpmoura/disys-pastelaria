<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Client\ClientResource;
use App\Http\Resources\Product\ProductResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid'       => $this->uuid,
            'client'     => new ClientResource($this->client),
            'products'   => new ProductResourceCollection($this->products),
            'total'      => $this->total,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
