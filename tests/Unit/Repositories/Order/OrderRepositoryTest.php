<?php

namespace Tests\Unit\Repositories\Order;

use App\Models\{Order, Product};
use App\Repositories\Order\{OrderRepository, OrderRepositoryInterface};
use Illuminate\Container\Container as Application;
use Illuminate\Foundation\Testing\Concerns\InteractsWithContainer;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Tests\TestCase;

class OrderRepositoryTest extends TestCase
{
    use InteractsWithContainer;

    protected $app;

    private readonly OrderRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = $this->createPartialMock(Application::class, ['make']);
        $this->app->expects($this->atLeastOnce())
            ->method('make')
            ->with(Order::class, [])
            ->willReturn(new Order());

        $this->repository = new OrderRepository($this->app);
    }

    /**
     * @test
     */
    public function shouldCreateOrder()
    {
        $attributes = Order::factory()->make()->toArray();

        $order = $this->repository->create($attributes);

        $this->assertInstanceOf(Order::class, $order);
    }

    /**
     * @test
     */
    public function shouldEditOrder()
    {
        $order = Order::factory()->create();

        $attributes = [
            'total' => (float)fake()->numerify('#.##'),
        ];

        $result = $this->repository->update($attributes, $order->id);

        $this->assertInstanceOf(Order::class, $result);
        $this->assertEquals($result->total, $attributes['total']);
    }

    /**
     * @test
     */
    public function shouldSyncProductsInCreateOrder()
    {
        $order   = Order::factory()->create();
        $product = Product::factory()->create();

        $result = $this->repository->sync($order->id, 'products', $product->id);

        $this->assertIsArray($result);
        $this->assertCount(1, $result['attached']);
        $this->assertCount(0, $result['detached']);
        $this->assertCount(0, $result['updated']);
        $this->assertEquals($product->id, $result['attached'][0]);
    }

    /**
     * @test
     */
    public function shouldSyncProductsInUpdateOrder()
    {
        $order   = Order::factory()->create();
        $product = Product::factory()->create();
        $order->products()->sync($product->id);

        $newProduct = Product::factory()->create();

        $result = $this->repository->sync($order->id, 'products', $newProduct->id);

        $this->assertIsArray($result);
        $this->assertCount(1, $result['attached']);
        $this->assertEquals($newProduct->id, $result['attached'][0]);
        $this->assertCount(1, $result['detached']);
        $this->assertEquals($product->id, $result['detached'][0]);
        $this->assertCount(0, $result['updated']);
    }

    /**
     * @test
     */
    public function shouldFindOrderByField()
    {
        $order = Order::factory()->create();

        $result = $this->repository->findBy('uuid', $order->uuid)->first();

        $this->assertInstanceOf(Order::class, $result);
        $this->assertEquals($order->uuid, $result->uuid);
        $this->assertEquals($order->total, $result->total);
    }

    /**
     * @test
     */
    public function shouldNotFoundOrder()
    {
        Order::factory(3)->create();

        $result = $this->repository->findBy('uuid', fake()->uuid)->first();

        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function shouldNotFoundOrders()
    {
        Order::factory(3)->create();

        $result = $this->repository->findBy('uuid', fake()->uuid);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(0, $result);
    }

    /**
     * @test
     */
    public function shouldReturnAllOrders()
    {
        Order::factory()->create();

        $result = $this->repository->all();

        $this->assertInstanceOf(Collection::class, $result);

        foreach ($result as $client) {
            $this->assertInstanceOf(Order::class, $client);
        }
    }

    /**
     * @test
     */
    public function shouldReturnPaginatedResults()
    {
        $mockContainer = \Mockery::mock(new Application());
        $this->mock(Application::class, fn () => $mockContainer);
        $mockContainer->expects('make')->twice()->withArgs([Order::class])->andReturn(new Order());
        $mockContainer->expects('make')->once()->withArgs(['request'])->andReturn(new Request());

        $repository = new OrderRepository($mockContainer);

        Order::factory()->create();

        $result = $repository->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    /**
     * @test
     */
    public function shouldDeleteOrder()
    {
        $client = Order::factory()->create();

        $result = $this->repository->delete($client->id);

        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

}
