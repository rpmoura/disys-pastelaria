<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\Models\Client;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateClient()
    {
        $attributes = [
            'name'         => 'Cliente 1',
            'email'        => 'cliente.um@gmail.com',
            'phone'        => '67998765432',
            'birth_date'   => '1994-06-28',
            'address'      => 'Rua Teste',
            'neighborhood' => 'Bairro',
            'add_on'       => null,
            'postcode'     => '01234000',
        ];

        $response = $this->post('/v1/clients', ['client' => $attributes]);

        $response->assertStatus(201);
        $response->assertJsonStructure(
            [
                'data' => [
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
                ],
            ]
        );
    }

    /**
     * @test
     */
    public function shouldEditClient()
    {
        $client = Client::factory()->create();

        $attributes = [
            'name' => 'New Name',
        ];

        $response = $this->put("/v1/clients/{$client->uuid}", ['client' => $attributes]);

        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
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
                ],
            ]
        );
        $this->assertEquals('New Name', $response->json('data.client.name'));
    }

    /**
     * @test
     */
    public function shouldNotCreateClientBecauseExistsAlreadyExists()
    {
        $oldClient = Client::factory()->create();

        $attributes = [
            'name'         => 'Cliente 1',
            'email'        => $oldClient->email,
            'phone'        => '67998765432',
            'birth_date'   => '1994-06-28',
            'address'      => 'Rua Teste',
            'neighborhood' => 'Bairro',
            'add_on'       => null,
            'postcode'     => '01234000',
        ];

        $response = $this->post("/v1/clients/", ['client' => $attributes]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function shouldNotEditClientBecauseExistsAlreadyExists()
    {
        $oldClient = Client::factory()->create();
        $client    = Client::factory()->create();

        $attributes = [
            'email' => $oldClient->email,
        ];

        $response = $this->put("/v1/clients/{$client->uuid}", ['client' => $attributes]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function shouldNotFoundClientForEdit()
    {
        Client::factory()->create();

        $attributes = [
            'name' => 'New Name',
        ];

        $response = $this->put('/v1/clients/' . fake()->uuid(), ['client' => $attributes]);

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function shouldFindClient()
    {
        $client = Client::factory()->create();

        $response = $this->get("/v1/clients/{$client->uuid}");

        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
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

        $response = $this->get('/v1/clients/' . fake()->uuid());

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function shouldListClientsWithoutPagination()
    {
        Client::factory(2)->create();

        $response = $this->get("/v1/clients");

        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    'clients' => [
                        [
                            'name',
                            'email',
                            'phone',
                            'birth_date',
                            'address',
                            'neighborhood',
                            'add_on',
                            'postcode',
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * @test
     */
    public function shouldListClientsWithPagination()
    {
        Client::factory(2)->create();

        $response = $this->get("/v1/clients?page=1");

        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    'clients' => [
                        'data' => [
                            [
                                'name',
                                'email',
                                'phone',
                                'birth_date',
                                'address',
                                'neighborhood',
                                'add_on',
                                'postcode',
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
    public function shouldDeleteClient()
    {
        $client = Client::factory()->create();

        $response = $this->delete("/v1/clients/{$client->uuid}");

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function shouldNotFoundClientForDelete()
    {
        Client::factory()->create();

        $response = $this->delete('/v1/clients/' . fake()->uuid());

        $response->assertStatus(404);
    }
}
