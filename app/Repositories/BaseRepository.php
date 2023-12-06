<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use Illuminate\Container\Container as Application;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\{Builder, Collection, Model};

abstract class BaseRepository implements BaseRepositoryInterface
{
    private Model|Builder|string|null $model;

    /**
     * @throws RepositoryException
     * @throws BindingResolutionException
     */
    public function __construct(private readonly Application $app)
    {
        $this->makeModel();
    }

    /**
     * @throws RepositoryException
     * @throws BindingResolutionException
     */
    private function makeModel(): Model
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException(
                "The class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model"
            );
        }

        return $this->model = $model;
    }

    /**
     * @throws RepositoryException
     * @throws BindingResolutionException
     */
    private function resetModel(): Model
    {
        return $this->makeModel();
    }

    public function findBy(string $key, mixed $value, array $columns = ['*']): Collection
    {
        return $this->model->query()->where($key, $value)->get($columns);
    }

    /**
     * @throws RepositoryException
     * @throws BindingResolutionException
     */
    public function create(array $attributes)
    {
        $model = $this->model->newInstance($attributes);
        $model->save();

        $this->resetModel();

        return $model;
    }

    /**
     * @throws RepositoryException
     * @throws BindingResolutionException
     */
    public function update(array $attributes, int $id)
    {
        $model = $this->model->findOrFail($id);
        $model->fill($attributes);
        $model->save();

        $this->resetModel();

        return $model;
    }

    /**
     * @throws RepositoryException
     * @throws BindingResolutionException
     */
    public function paginate(int $limit = null, array $columns = ['*'], string $method = "paginate")
    {
        $limit = is_null($limit) ? config('pagination.limit', 15) : $limit;

        $results = $this->model->{$method}($limit, $columns);
        $results->appends($this->app->make('request')->query());

        $this->resetModel();

        return $results;
    }

    /**
     * @throws RepositoryException
     * @throws BindingResolutionException
     */
    public function all(array $columns = ['*'])
    {
        if ($this->model instanceof Builder) {
            $results = $this->model->get($columns);
        } else {
            $results = $this->model->all($columns);
        }

        $this->resetModel();

        return $results;
    }

    /**
     * @throws RepositoryException
     * @throws BindingResolutionException
     */
    public function get(array $columns = ['*'])
    {
        return $this->all($columns);
    }

    public function delete(int $id)
    {
        return $this->model->findOrFail($id)->delete();
    }

    abstract public function model(): string;
}
