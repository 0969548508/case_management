<?php

namespace App\Repositories;

use App\Repositories\RepositoryInterface;
use Illuminate\Container\Container as App;
use Log;

abstract class Repository implements RepositoryInterface {
 
    /**
     * @var App
     */
    private $app;
 
    /**
     * @var
     */
    protected $model;
 
    /**
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
    }
 
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    abstract function model();
 
    /**
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        return $this->model->create($data);
    }
 
    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute="id")
    {
        return $this->model->where($attribute, '=', $id)->update($data);
    }
 
    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $response = $this->model->find($id);
        return $response->delete();
    }
 
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws RepositoryException
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());
        return $this->model = $model->newQuery();
    }

    /**
     * @var $id
     * @return array
     */
    public function findById($id)
    {
        $response = $this->model->find($id);
        return $response;
    }

    /**
     * @return array list all of user
     */
    public function getAll()
    {
        $response = $this->model->get();
        return $response;
    }

    /**
     * @var $id
     * @return array
     */
    public function findByColumn($columnName, $value, $sortColumn = null, $orderBy = 'ASC')
    {
        $response = $this->model->where($columnName, $value);
        if (!empty($sortColumn)) {
            $response = $response->orderBy($sortColumn, $orderBy);
        }
        $response = $response->get();

        return $response;
    }
}