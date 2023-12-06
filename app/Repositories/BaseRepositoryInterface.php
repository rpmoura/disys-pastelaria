<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\{Collection, Model};

interface BaseRepositoryInterface
{
    public function findBy(string $key, mixed $value, array $columns = ['*']): Model|Collection;

    public function create(array $attributes);

    public function update(array $attributes, int $id);
}
