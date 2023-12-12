<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\Events\OrderCreated;
use App\Models\{Client, Order, Product};
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateOrder()
    {
        $client  = Client::factory()->create();
        $product = Product::factory()->create();

        $attributes = [
            'client' => [
                'uuid' => $client->uuid,
            ],
            'products' => [
                [
                    'uuid' => $product->uuid,
                ],
            ],
        ];

        Event::fake([OrderCreated::class]);

        $response = $this->post('/v1/orders', ['order' => $attributes]);

        $response->assertStatus(201);
        $response->assertJsonStructure(
            [
                'data' => [
                    'order' => [
                        'client' => [
                            'name',
                            'email',
                            'phone',
                            'birth_date',
                            'address',
                            'neighborhood',
                            'add_on',
                            'postcode',
                        ],
                        'products' => [
                            [
                                'uuid',
                                'name',
                                'price',
                                'photo',
                                'created_at',
                            ],
                        ],
                        'total',
                        'created_at',
                    ],
                ],
            ]
        );
        Event::assertDispatched(OrderCreated::class);
    }

    /**
     * @test
     */
    public function shouldInvalidClientInCreateOrder()
    {
        $product = Product::factory()->create();

        $attributes = [
            'client' => [
                'uuid' => fake()->uuid(),
            ],
            'products' => [
                [
                    'uuid' => $product->uuid,
                ],
            ],
        ];

        $response = $this->post('/v1/orders', ['order' => $attributes]);

        $response->assertStatus(422);
        $this->assertEquals('The selected client is invalid.', $response->json('message'));
    }

    /**
     * @test
     */
    public function shouldInvalidProductInCreateOrder()
    {
        $client = Client::factory()->create();

        $attributes = [
            'client' => [
                'uuid' => $client->uuid,
            ],
            'products' => [
                [
                    'uuid' => fake()->uuid(),
                ],
            ],
        ];

        $response = $this->post('/v1/orders', ['order' => $attributes]);

        $response->assertStatus(422);
        $this->assertEquals('The selected product is invalid.', $response->json('message'));
    }

    /**
     * @test
     */
    public function shouldUpdateOrder()
    {
        $client  = Client::factory()->create();
        $order   = Order::factory()->create();
        $product = Product::factory()->create();
        $order->products()->sync($product->id);

        $attributes = [
            'client' => [
                'uuid' => $client->uuid,
            ],
            'products' => [
                [
                    'uuid' => $product->uuid,
                ],
            ],
        ];

        $response = $this->put('/v1/orders/' . $order->uuid, ['order' => $attributes]);

        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    'order' => [
                        'client' => [
                            'name',
                            'email',
                            'phone',
                            'birth_date',
                            'address',
                            'neighborhood',
                            'add_on',
                            'postcode',
                        ],
                        'products' => [
                            [
                                'uuid',
                                'name',
                                'price',
                                'photo',
                                'created_at',
                            ],
                        ],
                        'total',
                        'created_at',
                    ],
                ],
            ]
        );
    }

    /**
     * @test
     */
    public function shouldInvalidClientInUpdateOrder()
    {
        $order   = Order::factory()->create();
        $product = Product::factory()->create();
        $order->products()->sync($product->id);

        $attributes = [
            'client' => [
                'uuid' => fake()->uuid(),
            ],
            'products' => [
                [
                    'uuid' => $product->uuid,
                ],
            ],
        ];

        $response = $this->put('/v1/orders/' . $order->uuid, ['order' => $attributes]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function shouldInvalidProductInUpdateOrder()
    {
        $client  = Client::factory()->create();
        $order   = Order::factory()->create();
        $product = Product::factory()->create();
        $order->products()->sync($product->id);

        $attributes = [
            'client' => [
                'uuid' => $client->uuid,
            ],
            'products' => [
                [
                    'uuid' => fake()->uuid,
                ],
            ],
        ];

        $response = $this->put('/v1/orders/' . $order->uuid, ['order' => $attributes]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function shouldNotFoundOrderToUpdate()
    {
        $client  = Client::factory()->create();
        $order   = Order::factory()->create();
        $product = Product::factory()->create();
        $order->products()->sync($product->id);

        $attributes = [
            'client' => [
                'uuid' => $client->uuid,
            ],
            'products' => [
                [
                    'uuid' => $product->uuid,
                ],
            ],
        ];

        $response = $this->put('/v1/orders/' . fake()->uuid, ['order' => $attributes]);

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function shouldFindOrder()
    {
        $order   = Order::factory()->create();
        $product = Product::factory()->create();
        $order->products()->sync($product->id);

        $response = $this->get("/v1/orders/{$order->uuid}");

        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    'order' => [
                        'client',
                        'products' => [
                            [
                                'uuid',
                                'name',
                                'price',
                                'photo',
                                'created_at',
                            ],
                        ],
                        'total',
                        'created_at',
                    ],
                ],
            ]
        );
    }

    /**
     * @test
     */
    public function shouldNotFoundClient()
    {
        Client::factory()->create();

        $response = $this->get('/v1/orders/' . fake()->uuid());

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function shouldListOrdersWithoutPagination()
    {
        Client::factory()->create();
        $product = Product::factory()->create();
        $order   = Order::factory()->create();
        $order->products()->sync($product->id);

        $response = $this->get("/v1/orders");

        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    'orders' => [
                        [
                            'client',
                            'products' => [
                                [
                                    'uuid',
                                    'name',
                                    'price',
                                    'photo',
                                    'created_at',
                                ],
                            ],
                            'total',
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
    public function shouldListOrdersWithPagination()
    {
        Client::factory()->create();
        $product = Product::factory()->create();
        $order   = Order::factory()->create();
        $order->products()->sync($product->id);

        $response = $this->get("/v1/orders?page=1");

        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    'orders' => [
                        'data' => [
                            [
                                'client',
                                'products' => [
                                    [
                                        'uuid',
                                        'name',
                                        'price',
                                        'photo',
                                        'created_at',
                                    ],
                                ],
                                'total',
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
    public function shouldDeleteOrder()
    {
        $order = Order::factory()->create();

        $response = $this->delete("/v1/orders/{$order->uuid}");

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function shouldNotFoundOrderForDelete()
    {
        Order::factory()->create();

        $response = $this->delete('/v1/orders/' . fake()->uuid());

        $response->assertStatus(404);
    }
}
