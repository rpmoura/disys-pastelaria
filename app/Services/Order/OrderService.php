<?php

namespace App\Services\Order;

use App\Models\{Client, Order};
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderService implements OrderServiceInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $repository
    ) {
    }

    public function create(Client $client, Collection $products): Order
    {
        $attributes = [
            'client_id' => $client->id,
            'total'     => $products->sum('price'),
        ];

        $order = $this->repository->create($attributes);

        $this->repository->sync($order->id, 'products', $products->pluck('id')->toArray(), false);

        return $order->refresh();
    }

    public function update(Order $order, Client $client, Collection $products): Order
    {
        $attributes = [
            'client_id' => $client->id,
            'total'     => $products->sum('price'),
        ];

        $order = $this->repository->update($attributes, $order->id);

        $this->repository->sync($order->id, 'products', $products->pluck('id')->toArray());

        return $order->refresh();
    }

    public function findOneBy(string $key, int|string $value): Order
    {
        $order = $this->repository->findBy($key, $value)->first();

        if (!$order instanceof Order) {
            throw new NotFoundHttpException(__('exception.order.not_found'));
        }

        return $order;
    }

    public function findList(): OrderRepositoryInterface
    {
        return $this->repository;
    }

    public function delete(Order $order): bool
    {
        return $this->repository->delete($order->id);
    }
}
