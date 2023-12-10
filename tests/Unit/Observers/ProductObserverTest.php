<?php

namespace Tests\Unit\Observers;

use App\DTO\FileDelete;
use App\Models\Product;
use App\Observers\ProductObserver;
use App\Services\FileManager\FileManagerServiceInterface;
use Tests\TestCase;

class ProductObserverTest extends TestCase
{
    private readonly ProductObserver $observer;

    private readonly FileManagerServiceInterface $fileManagerService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileManagerService = \Mockery::mock(FileManagerServiceInterface::class);
        $this->app->instance(FileManagerServiceInterface::class, $this->fileManagerService);

        $this->observer = new ProductObserver($this->fileManagerService);
    }

    /**
     * @test
     */
    public function shouldDeletePhoto()
    {
        $product = Product::factory()->create();
        $product->setRawAttributes(['photo' => 'products/deleted.png']);

        $this->fileManagerService->shouldReceive('delete')->with(\Mockery::type(FileDelete::class))->once()->andReturns();

        $this->observer->updated($product);
    }

    /**
     * @test
     */
    public function shouldDeletePhotoOfDeletedProduct()
    {
        $attributes = Product::factory()->make()->toArray();
        $product    = (new Product())->newInstance()->forceFill($attributes);

        $this->fileManagerService->shouldReceive('delete')->with(\Mockery::type(FileDelete::class))->once()->andReturns();

        $this->observer->deleted($product);
    }
}
