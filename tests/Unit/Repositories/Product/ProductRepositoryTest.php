<?php

namespace Tests\Unit\Repositories\Product;

use App\Models\Product;
use App\Repositories\Product\{ProductRepository, ProductRepositoryInterface};
use Illuminate\Container\Container as Application;
use Illuminate\Foundation\Testing\Concerns\InteractsWithContainer;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ProductRepositoryTest extends TestCase
{
    use InteractsWithContainer;

    protected $app;

    private readonly ProductRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = $this->createPartialMock(Application::class, ['make']);
        $this->app->expects($this->atLeastOnce())
            ->method('make')
            ->with(Product::class, [])
            ->willReturn(new Product());

        $this->repository = new ProductRepository($this->app);
    }

    /**
     * @test
     */
    public function shouldFindProductByField()
    {
        $product = Product::factory()->create();

        $result = $this->repository->findBy('uuid', $product->uuid)->first();

        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals($product->uuid, $result->uuid);
        $this->assertEquals($product->name, $result->name);
        $this->assertEquals($product->price, $result->price);
        $this->assertEquals($product->photo, $result->photo);
    }

    /**
     * @test
     */
    public function shouldNotFoundProduct()
    {
        Product::factory(3)->create();

        $result = $this->repository->findBy('uuid', fake()->uuid)->first();

        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function shouldNotFoundProducts()
    {
        Product::factory(3)->create();

        $result = $this->repository->findBy('uuid', fake()->uuid);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(0, $result);
    }

    /**
     * @test
     */
    public function shouldCreateProduct()
    {
        $attributes = Product::factory()->make()->toArray();

        $product = $this->repository->create($attributes);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($attributes['name'], $product->name);
        $this->assertEquals($attributes['price'], $product->price);
        $this->assertEquals($attributes['photo'], $product->photo);
    }

    /**
     * @test
     */
    public function shouldUpdateProduct()
    {
        $existingProduct = Product::factory()->create();

        $updatedAttributes = [
            'name' => fake()->colorName(),
        ];

        $product = $this->repository->update($updatedAttributes, $existingProduct->id);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($updatedAttributes['name'], $product->name);
        $this->assertEquals($existingProduct->price, $product->price);
        $this->assertEquals($existingProduct->photo, $product->photo);
    }

    /**
     * @test
     */
    public function shouldReturnAllProducts()
    {
        Product::factory()->create();

        $result = $this->repository->all();

        $this->assertInstanceOf(Collection::class, $result);

        foreach ($result as $product) {
            $this->assertInstanceOf(Product::class, $product);
        }
    }

    /**
     * @test
     */
    public function shouldReturnGetProducts()
    {
        Product::factory()->create();

        $result = $this->repository->get();

        $this->assertInstanceOf(Collection::class, $result);

        foreach ($result as $product) {
            $this->assertInstanceOf(Product::class, $product);
        }
    }

    /**
     * @test
     */
    public function shouldReturnPaginatedResults()
    {
        $mockContainer = \Mockery::mock(new Application());
        $this->mock(Application::class, fn () => $mockContainer);
        $mockContainer->expects('make')->twice()->withArgs([Product::class])->andReturn(new Product());
        $mockContainer->expects('make')->once()->withArgs(['request'])->andReturn(new Request());

        $repository = new ProductRepository($mockContainer);

        Product::factory()->create();

        $result = $repository->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    /**
     * @test
     */
    public function shouldDeleteProduct()
    {
        Product::flushEventListeners();

        $product = Product::factory()->create();

        $result = $this->repository->delete($product->id);

        $this->assertIsBool($result);
        $this->assertTrue($result);
    }
}
