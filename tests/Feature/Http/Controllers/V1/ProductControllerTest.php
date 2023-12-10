<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Tests\Fixture\ImageFixture;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateProduct()
    {
        $attributes = [
            'name'  => 'Product 1',
            'price' => 123.45,
            'photo' => ImageFixture::getImageBase64Encoded(),
        ];

        Storage::fake();
        Storage::shouldReceive('disk')->withNoArgs()->twice()->andReturnSelf();
        Storage::shouldReceive('put')->withAnyArgs()->once()->andReturnTrue();
        Storage::shouldReceive('url')->withAnyArgs()->once()->andReturn('products/test.png');

        $response = $this->post('/v1/products', ['product' => $attributes]);

        $response->assertStatus(201);
        $response->assertJsonStructure(
            [
                'data' => [
                    'product' => [
                        'name',
                        'price',
                        'photo',
                        'created_at',
                    ],
                ],
            ]
        );
    }

    /**
     * @test
     */
    public function shouldEditProduct()
    {
        $product = Product::factory()->create();

        $attributes = [
            'name' => 'New Name',
        ];

        Storage::fake();
        Storage::shouldReceive('disk')->withNoArgs()->once()->andReturnSelf();
        Storage::shouldReceive('put')->withAnyArgs()->never();
        Storage::shouldReceive('url')->withAnyArgs()->once()->andReturn('products/test.png');

        $response = $this->put("/v1/products/{$product->uuid}", ['product' => $attributes]);

        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    'product' => [
                        'name',
                        'price',
                        'photo',
                        'created_at',
                    ],
                ],
            ]
        );
        $this->assertEquals('New Name', $response->json('data.product.name'));
    }

    /**
     * @test
     */
    public function shouldEditProductWithNewPhoto()
    {
        $product = Product::factory()->create();

        $attributes = [
            'photo' => ImageFixture::getImageBase64Encoded(),
        ];

        Storage::fake();
        Storage::shouldReceive('disk')->withNoArgs()->times(3)->andReturnSelf();
        Storage::shouldReceive('put')->withAnyArgs()->once()->andReturnTrue();
        Storage::shouldReceive('delete')->withAnyArgs()->once()->andReturnTrue();
        Storage::shouldReceive('url')->withAnyArgs()->once()->andReturn('products/test.png');

        $response = $this->put("/v1/products/{$product->uuid}", ['product' => $attributes]);

        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    'product' => [
                        'name',
                        'price',
                        'photo',
                        'created_at',
                    ],
                ],
            ]
        );
        $this->assertNotEquals($product->photo, $response->json('data.product.photo'));
    }

    /**
     * @test
     */
    public function shouldNotFoundProductForEdit()
    {
        Product::factory()->create();

        $attributes = [
            'name' => 'New Name',
        ];

        $response = $this->put('/v1/products/' . fake()->uuid(), ['product' => $attributes]);

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function shouldFindProduct()
    {
        $product = Product::factory()->create();

        Storage::fake();
        Storage::shouldReceive('disk')->withNoArgs()->once()->andReturnSelf();
        Storage::shouldReceive('put')->never();
        Storage::shouldReceive('url')->withAnyArgs()->once()->andReturn('products/test.png');

        $response = $this->get("/v1/products/{$product->uuid}");

        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    'product' => [
                        'name',
                        'price',
                        'photo',
                        'created_at',
                    ],
                ],
            ]
        );
    }

    /**
     * @test
     */
    public function shouldNotFoundProduct()
    {
        Product::factory()->create();

        $response = $this->get('/v1/products/' . fake()->uuid());

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function shouldListProductsWithoutPagination()
    {
        Product::factory(2)->create();

        Storage::fake();
        Storage::shouldReceive('disk')->withNoArgs()->twice()->andReturnSelf();
        Storage::shouldReceive('url')->withAnyArgs()->twice()->andReturn('products/test.png');

        $response = $this->get("/v1/products");

        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    'products' => [
                        [
                            'name',
                            'price',
                            'photo',
                            'created_at',
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * @test
     */
    public function shouldListProductsWithPagination()
    {
        Product::factory(2)->create();

        Storage::fake();
        Storage::shouldReceive('disk')->withNoArgs()->twice()->andReturnSelf();
        Storage::shouldReceive('url')->withAnyArgs()->twice()->andReturn('products/test.png');

        $response = $this->get("/v1/products?page=1");

        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    'products' => [
                        'data' => [
                            [
                                'name',
                                'price',
                                'photo',
                                'created_at',
                            ],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * @test
     */
    public function shouldDeleteProduct()
    {
        $product = Product::factory()->create();

        Storage::fake();
        Storage::shouldReceive('disk')->withNoArgs()->once()->andReturnSelf();
        Storage::shouldReceive('delete')->withAnyArgs()->once()->andReturn('products/test.png');

        $response = $this->delete("/v1/products/{$product->uuid}");

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function shouldNotFoundProductForDelete()
    {
        Product::factory()->create();

        $response = $this->delete('/v1/products/' . fake()->uuid());

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionOnUploadPhotoCreateProduct()
    {
        $attributes = [
            'name'  => 'Product 1',
            'price' => 123.45,
            'photo' => ImageFixture::getImageBase64Encoded(),
        ];

        Storage::fake();
        Storage::shouldReceive('disk')->withNoArgs()->once()->andReturnSelf();
        Storage::shouldReceive('put')->withAnyArgs()->once()->andReturnFalse();

        $response = $this->post("/v1/products", ['product' => $attributes]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionOnUploadEditedPhoto()
    {
        $product = Product::factory()->create();

        $attributes = [
            'photo' => ImageFixture::getImageBase64Encoded(),
        ];

        Storage::fake();
        Storage::shouldReceive('disk')->withNoArgs()->once()->andReturnSelf();
        Storage::shouldReceive('put')->withAnyArgs()->once()->andReturnFalse();

        $response = $this->put("/v1/products/{$product->uuid}", ['product' => $attributes]);

        $response->assertStatus(422);
    }
}
