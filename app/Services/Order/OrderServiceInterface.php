<?php

namespace App\Services\Order;

use App\Models\{Client, Order};
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Support\Collection;

interface OrderServiceInterface
{
    public function create(Client $client, Collection $products): Order;

    public function update(Order $order, Client $client, Collection $products): Order;

    public function findOneBy(string $key, int|string $value): Order;

    public function findList(): OrderRepositoryInterface;

    public function delete(Order $order): bool;
}
