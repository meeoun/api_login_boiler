<?php
namespace App\Repositories\Eloquent;

use App\Models\models\Design;
use App\Repositories\Contracts\IBase;
use App\Repositories\Criteria\ICriteria;
use Illuminate\Support\Arr;


abstract class BaseRepository implements IBase, ICriteria
{
    protected $model;

    public function __construct()
    {
        $this->model = $this->getModelClass();
    }


    protected function getModelClass()
    {
        if(!method_exists($this, 'model'))
        {
            throw new \Exception('No Model Defined');
        }
        return app()->make($this->model());

    }


    public function all()
    {
        return $this->model->get();
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }



    public function findWhere($column, $value)
    {
        return $this->model->where($column, $value)->get();

    }
    public function findWhereFirst($column, $value)
    {
        return $this->model->where($column, $value)->firstOrFail();
    }
    public function paginate($perPage = 10)
    {
        return $this->model->paginate($perPage);
    }
    public function create(array $data)
    {
        return $this->model->create($data);

    }
    public function update($model, array $data)
    {
        $model->update($data);
        return $model;
    }
    public function delete($model)
    {
        return $model->delete();

    }

    public function withCriteria(...$criteria)
    {
        $criteria = Arr::flatten($criteria);
        foreach($criteria as $criterion)
        {
            $this->model = $criterion->apply($this->model);
        }

        return $this;


    }

}
