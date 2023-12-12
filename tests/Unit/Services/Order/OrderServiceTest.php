<?php

namespace Tests\Unit\Services\Order;

use App\Models\{Client, Order, Product};
use App\Repositories\Order\{OrderRepository, OrderRepositoryInterface};
use App\Services\Order\{OrderService, OrderServiceInterface};
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    private OrderServiceInterface $service;

    private OrderRepositoryInterface $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(OrderRepository::class);
        $this->service    = new OrderService($this->repository);
    }

    /**
     * @test
     */
    public function shouldCreateOrder()
    {
        $client              = Client::factory()->create();
        $products            = Product::factory(2)->create();
        $attributes          = Order::factory()->make()->toArray();
        $attributes['total'] = $products->sum('price');

        $this->repository->expects($this->once())
            ->method('create')
            ->with($attributes)
            ->willReturn(
                (new Order())->forceFill(array_merge($attributes, ['id' => 1, 'total' => $products->sum('price')]))
            );

        $this->repository->expects($this->once())
            ->method('sync')
            ->with(1, 'products', [1, 2], false)
            ->willReturn([]);

        $client = $this->service->create($client, $products);

        $this->assertInstanceOf(Order::class, $client);
    }

    /**
     * @test
     */
    public function shouldUpdateOrder()
    {
        $client       = Client::factory()->create();
        $product      = Product::factory()->create();
        $otherProduct = Product::factory()->create();
        $order        = Order::factory()->create();
        $order->products()->sync($product->id);

        $attributes = [
            'client_id' => $client->id,
            'total'     => $otherProduct->price,
        ];

        $this->repository->expects($this->once())
            ->method('update')
            ->with($attributes, $order->id)
            ->willReturn(
                (new Order())->forceFill(array_merge($attributes, ['id' => 1, 'total' => $otherProduct->price]))
            );

        $this->repository->expects($this->once())
            ->method('sync')
            ->with(1, 'products', [2], true)
            ->willReturn([]);

        $client = $this->service->update($order, $client, collect([$otherProduct]));

        $this->assertInstanceOf(Order::class, $client);
    }

    /**
     * @test
     */
    public function shouldNotFoundOrderByField()
    {
        $uuid = fake()->uuid();

        $this->repository->expects($this->once())
            ->method('findBy')
            ->with('uuid', $uuid)
            ->willReturn(new Collection());

        $this->expectException(NotFoundHttpException::class);

        $this->service->findOneBy('uuid', $uuid);
    }

    /**
     * @test
     */
    public function shouldFindOrderByField()
    {
        $order = Order::factory()->create();

        $this->repository->expects($this->once())
            ->method('findBy')
            ->with('uuid', $order->uuid)
            ->willReturn(new Collection([$order]));

        $result = $this->service->findOneBy('uuid', $order->uuid);

        $this->assertInstanceOf(Order::class, $result);
        $this->assertEquals($order->uuid, $result->uuid);
    }

    /**
     * @test
     */
    public function shouldFindAllClients()
    {
        $order = Order::factory()->create();

        $this->repository->expects($this->once())
            ->method('get')
            ->willReturn(new Collection([$order]));

        $result = $this->service->findList()->get();

        $this->assertCount(1, $result);
        $this->assertInstanceOf(Collection::class, $result);
    }

    /**
     * @test
     */
    public function shouldNotFoundClients()
    {
        $this->repository->expects($this->once())
            ->method('get')
            ->willReturn(new Collection());

        $result = $this->service->findList()->get();

        $this->assertEmpty($result);
        $this->assertInstanceOf(Collection::class, $result);
    }

    /**
     * @test
     */
    public function shouldDeleteClient()
    {
        $order = Order::factory()->create();

        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(true);

        $result = $this->service->delete($order);

        $this->assertIsBool($result);
        $this->assertTrue($result);
    }
}
