<?php

namespace Tests\Unit\Repositories\Client;

use App\Models\Client;
use App\Repositories\Client\{ClientRepository, ClientRepositoryInterface};
use Illuminate\Container\Container as Application;
use Illuminate\Foundation\Testing\Concerns\InteractsWithContainer;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ClientRepositoryTest extends TestCase
{
    use InteractsWithContainer;

    protected $app;

    private readonly ClientRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = $this->createPartialMock(Application::class, ['make']);
        $this->app->expects($this->atLeastOnce())
            ->method('make')
            ->with(Client::class, [])
            ->willReturn(new Client());

        $this->repository = new ClientRepository($this->app);
    }

    /**
     * @test
     */
    public function shouldFindClientByField()
    {
        $client = Client::factory()->create();

        $result = $this->repository->findBy('uuid', $client->uuid)->first();

        $this->assertInstanceOf(Client::class, $result);
        $this->assertEquals($client->uuid, $result->uuid);
        $this->assertEquals($client->email, $result->email);
    }

    /**
     * @test
     */
    public function shouldNotFoundClient()
    {
        Client::factory(3)->create();

        $result = $this->repository->findBy('uuid', fake()->uuid)->first();

        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function shouldNotFoundClients()
    {
        Client::factory(3)->create();

        $result = $this->repository->findBy('uuid', fake()->uuid);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(0, $result);
    }

    /**
     * @test
     */
    public function shouldCreateClient()
    {
        $attributes = Client::factory()->make()->toArray();

        $client = $this->repository->create($attributes);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals($attributes['name'], $client->name);
        $this->assertEquals($attributes['email'], $client->email);
    }

    /**
     * @test
     */
    public function shouldUpdateClient()
    {
        $existingClient = Client::factory()->create();

        $updatedAttributes = [
            'name'  => fake()->name(),
            'phone' => fake()->e164PhoneNumber(),
        ];

        $client = $this->repository->update($updatedAttributes, $existingClient->id);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals($existingClient->email, $client->email);
        $this->assertEquals($updatedAttributes['name'], $client->name);
        $this->assertEquals($updatedAttributes['phone'], $client->phone);
    }

    /**
     * @test
     */
    public function shouldReturnAllClients()
    {
        Client::factory()->create();

        $result = $this->repository->all();

        $this->assertInstanceOf(Collection::class, $result);

        foreach ($result as $client) {
            $this->assertInstanceOf(Client::class, $client);
        }
    }

    /**
     * @test
     */
    public function shouldReturnGetClients()
    {
        Client::factory()->create();

        $result = $this->repository->get();

        $this->assertInstanceOf(Collection::class, $result);

        foreach ($result as $client) {
            $this->assertInstanceOf(Client::class, $client);
        }
    }

    /**
     * @test
     */
    public function shouldReturnPaginatedResults()
    {
        $mockContainer = \Mockery::mock(new Application());
        $this->mock(Application::class, fn () => $mockContainer);
        $mockContainer->expects('make')->twice()->withArgs([Client::class])->andReturn(new Client());
        $mockContainer->expects('make')->once()->withArgs(['request'])->andReturn(new Request());

        $repository = new ClientRepository($mockContainer);

        Client::factory()->create();

        $result = $repository->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }
}
