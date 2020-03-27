<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\ModelNotDefinedException;
use App\Repositories\Contracts\BaseInterface;

abstract class BaseRepository implements BaseInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = $this->getModelClass();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findWhere($column, $value)
    {
        return $this->model->where($column, $value)->get();
    }

    public function findWhereFirst($column, $value)
    {
        return $this->model->where($column, $value)->first();
    }

    public function paginate($perPage = 10)
    {
        return $this->model->paginate($perPage);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        $record = $this->find($id);
        return $record->delete();
    }

    public function withCriteria(...$criteria)
    {
        $criteria = Arr::flatten($criteria);

        foreach ($criteria as $criterion){
            $this->model = $criterion->apply($this->model);
        }

        return $this;
    }

    /**
     * Get the sub model that access the BaseRepository
     */
    protected function getModelClass()
    {
        if (!method_exists($this, 'model')) {
            throw new ModelNotDefinedException();
        }

        return app()->make($this->model());
    }
}
