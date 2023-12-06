<?php

namespace App\Services\Client;

use App\Models\Client;

interface ClientServiceInterface
{
    public function create(array $attributes): Client;

    public function update(Client $client, array $attributes): Client;

    public function findOneBy(string $key, int|string $value);
}
