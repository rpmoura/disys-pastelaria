<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\{Collection, Model};

interface BaseRepositoryInterface
{
    public function findBy(string $key, mixed $value, array $columns = ['*']): Model|Collection;

    public function create(array $attributes);

    public function update(array $attributes, int $id);

    public function paginate(int $limit = null, array $columns = ['*'], string $method = "paginate");

    public function all(array $columns = ['*']);

    public function get(array $columns = ['*']);

    public function delete(int $id);
}
