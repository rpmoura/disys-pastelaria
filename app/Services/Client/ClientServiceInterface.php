<?php

namespace App\Services\Client;

use App\Models\Client;
use App\Repositories\Client\ClientRepositoryInterface;

interface ClientServiceInterface
{
    public function create(array $attributes): Client;

    public function update(Client $client, array $attributes): Client;

    public function findOneBy(string $key, int|string $value): Client;

    public function findList(): ClientRepositoryInterface;

    public function delete(Client $client): bool;
}
