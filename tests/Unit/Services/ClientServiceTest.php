<?php

namespace Tests\Unit\Services;

use App\Models\Client;
use App\Repositories\Client\{ClientRepository, ClientRepositoryInterface};
use App\Services\Client\{ClientService, ClientServiceInterface};
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class ClientServiceTest extends TestCase
{
    private ClientServiceInterface $service;

    private ClientRepositoryInterface $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->getMockBuilder(ClientRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->service = new ClientService($this->repository);
    }

    /**
     * @test
     */
    public function shouldCreateClient()
    {
        $attributes = Client::factory()->make()->toArray();

        $this->repository->expects($this->once())
            ->method('create')
            ->with($attributes)
            ->willReturn(new Client($attributes));

        $client = $this->service->create($attributes);

        $this->assertInstanceOf(Client::class, $client);
    }

    /**
     * @test
     */
    public function shouldUpdateClient()
    {
        $attributes     = Client::factory()->make(['id' => 1])->toArray();
        $existingClient = (new Client())->newInstance()->forceFill($attributes);

        $updatedAttributes = ['name' => 'New Name'];

        $this->repository->expects($this->once())
            ->method('update')
            ->with($attributes, 1)
            ->willReturn((new Client())->forceFill(array_merge($existingClient->toArray(), $updatedAttributes)));

        $client = $this->service->update($existingClient, $attributes);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals('New Name', $client->name);
        $this->assertEquals($existingClient->uuid, $client->uuid);
    }

    /**
     * @test
     */
    public function shouldNotFoundClientByField()
    {
        $this->repository->expects($this->once())
            ->method('findBy')
            ->with('uuid', )
            ->willReturn(new Collection());

        $this->expectException(NotFoundHttpException::class);

        $this->service->findOneBy('uuid', fake()->uuid());
    }

    /**
     * @test
     */
    public function shouldFindClientByField()
    {
        $attributes = Client::factory()->make(['id' => 1])->toArray();
        $client     = (new Client())->newInstance()->forceFill($attributes);

        $this->repository->expects($this->once())
            ->method('findBy')
            ->with('uuid', )
            ->willReturn(new Collection([$client]));

        $result = $this->service->findOneBy('uuid', $client->uuid);

        $this->assertInstanceOf(Client::class, $result);
        $this->assertEquals($client->uuid, $result->uuid);
        $this->assertEquals($client->email, $result->email);
    }

    /**
     * @test
     */
    public function shouldFindAllClients()
    {
        $attributes = Client::factory()->make(['id' => 1])->toArray();
        $client     = (new Client())->newInstance()->forceFill($attributes);

        $this->repository->expects($this->once())
            ->method('get')
            ->willReturn(new Collection([$client]));

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
        $attributes     = Client::factory()->make(['id' => 1])->toArray();
        $client = (new Client())->newInstance()->forceFill($attributes);

        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(true);

        $result = $this->service->delete($client);

        $this->assertIsBool($result);
        $this->assertTrue($result);
    }
}
