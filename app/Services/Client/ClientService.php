<?php

namespace App\Services\Client;

use App\Models\Client;
use App\Repositories\Client\{ClientRepositoryInterface};
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClientService implements ClientServiceInterface
{
    public function __construct(private readonly ClientRepositoryInterface $repository)
    {
    }

    public function create(array $attributes): Client
    {
        return $this->repository->create($attributes);
    }

    public function findOneBy(string $key, int|string $value): Client
    {
        $client = $this->repository->findBy($key, $value)->first();

        if (!$client instanceof Client) {
            throw new NotFoundHttpException(__('exception.client.not_found'));
        }

        return $client;
    }

    public function update(Client $client, array $attributes): Client
    {
        return $this->repository->update($attributes, $client->id);
    }

    public function findList(): ClientRepositoryInterface
    {
        return $this->repository;
    }
}
