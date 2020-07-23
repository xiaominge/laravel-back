<?php

namespace App\Foundation\Repository;

use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Repository
 * @package App\Foundation\Repository
 */
abstract class Repository implements RepositoryInterface
{
    /**
     * @var \App\Foundation\Model|mixed
     */
    protected $model;
    /**
     * @var \Illuminate\Database\Eloquent\Builder|null
     */
    protected $query = null;

    /**
     * Repository constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $model = $this->makeModel();
        return $model;
    }

    /**
     * Instantiation model
     *
     * @return Model|mixed
     * @throws Exception
     */
    public function makeModel()
    {
        $model = app($this->model());
        if (!$model instanceof Model) {
            throw new Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * Get attribute model
     *
     * @return Model|\Illuminate\Database\Eloquent\Builder
     */
    public function m()
    {
        return $this->model;
    }

    /**
     * Get attribute query
     *
     * @param bool $is_new
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getQuery($is_new = false)
    {
        return $this->query || $is_new ? $this->query : $this->model->query();
    }
}
