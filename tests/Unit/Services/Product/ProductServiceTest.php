<?php

namespace Tests\Unit\Services\Product;

use App\Models\Product;
use App\Repositories\Product\{ProductRepository, ProductRepositoryInterface};
use App\Services\FileManager\FileManagerServiceInterface;
use App\Services\Product\{ProductService, ProductServiceInterface};
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\Fixture\ImageFixture;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    private ProductServiceInterface $service;

    private ProductRepositoryInterface $repository;

    private FileManagerServiceInterface $fileServiceMock;
    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->getMockBuilder(ProductRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->fileServiceMock = \Mockery::mock(FileManagerServiceInterface::class);
        $this->app->instance(FileManagerServiceInterface::class, $this->fileServiceMock);

        $this->service = new ProductService($this->repository, $this->fileServiceMock);
    }

    /**
     * @test
     */
    public function shouldCreateProduct()
    {
        $this->fileServiceMock
            ->shouldReceive('upload')
            ->once()
            ->withAnyArgs()
            ->andReturn('products/default.png');

        $attributes = Product::factory()->make()->toArray();

        $this->repository->expects($this->once())
            ->method('create')
            ->with($attributes)
            ->willReturn(new Product($attributes));

        $attributes['photo'] = ImageFixture::getImageBase64Encoded();

        $product = $this->service->create($attributes);

        $this->assertInstanceOf(Product::class, $product);
    }

    /**
     * @test
     */
    public function shouldUpdateProduct()
    {
        Product::flushEventListeners();

        $this->fileServiceMock
            ->shouldReceive('upload')
            ->once()
            ->withAnyArgs()
            ->andReturn('products/new_image.png');

        $attributes      = Product::factory()->make(['id' => 1])->toArray();
        $existingProduct = (new Product())->newInstance()->forceFill($attributes);

        $updatedAttributes = ['name' => 'New Name', 'photo' => 'products/new_image.png'];

        $this->repository->expects($this->once())
            ->method('update')
            ->with($updatedAttributes, 1)
            ->willReturn((new Product())->forceFill(array_merge($existingProduct->toArray(), $updatedAttributes)));

        $updatedAttributes['photo'] = ImageFixture::getImageBase64Encoded();

        $product = $this->service->update($existingProduct, $updatedAttributes);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('New Name', $product->name);
        $this->assertEquals($existingProduct->uuid, $product->uuid);
        $this->assertEquals('products/new_image.png', $product->photo);
    }

    /**
     * @test
     */
    public function shouldUpdateProductWithoutPhoto()
    {
        Product::flushEventListeners();

        $this->fileServiceMock
            ->shouldReceive('upload')
            ->never();

        $attributes      = Product::factory()->make(['id' => 1])->toArray();
        $existingProduct = (new Product())->newInstance()->forceFill($attributes);

        $updatedAttributes = ['name' => 'New Name'];

        $this->repository->expects($this->once())
            ->method('update')
            ->with($updatedAttributes, 1)
            ->willReturn((new Product())->forceFill(array_merge($existingProduct->toArray(), $updatedAttributes)));

        $product = $this->service->update($existingProduct, $updatedAttributes);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('New Name', $product->name);
        $this->assertEquals($existingProduct->uuid, $product->uuid);
        $this->assertEquals($existingProduct->photo, $product->photo);
    }

    /**
     * @test
     */
    public function shouldNotFoundProductByField()
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
    public function shouldFindProductByField()
    {
        $attributes = Product::factory()->make(['id' => 1])->toArray();
        $product    = (new Product())->newInstance()->forceFill($attributes);

        $this->repository->expects($this->once())
            ->method('findBy')
            ->with('uuid', $product->uuid)
            ->willReturn(new Collection([$product]));

        $result = $this->service->findOneBy('uuid', $product->uuid);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals($product->uuid, $result->uuid);
        $this->assertEquals($product->email, $result->email);
    }

    /**
     * @test
     */
    public function shouldFindAllProducts()
    {
        $attributes = Product::factory()->make(['id' => 1])->toArray();
        $product    = (new Product())->newInstance()->forceFill($attributes);

        $this->repository->expects($this->once())
            ->method('get')
            ->willReturn(new Collection([$product]));

        $result = $this->service->findList()->get();

        $this->assertCount(1, $result);
        $this->assertInstanceOf(Collection::class, $result);
    }

    /**
     * @test
     */
    public function shouldNotFoundProducts()
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
    public function shouldDeleteProduct()
    {
        Product::flushEventListeners();

        $attributes = Product::factory()->make(['id' => 1])->toArray();
        $product    = (new Product())->newInstance()->forceFill($attributes);

        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(true);

        $result = $this->service->delete($product);

        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function shouldFindProductsByField()
    {
        $attributes = Product::factory()->make(['id' => 1])->toArray();
        $product    = (new Product())->newInstance()->forceFill($attributes);

        $this->repository->expects($this->once())
            ->method('findWhereIn')
            ->with('uuid', [$product->uuid])
            ->willReturn(new Collection([$product]));

        $result = $this->service->findBy('uuid', $product->uuid);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(1, $result);
    }
}
