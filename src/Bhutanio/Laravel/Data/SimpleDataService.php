<?php

namespace Bhutanio\Laravel\Data;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SimpleDataService.
 *
 * @method DataService format(array $data);
 * @method DataService create(array $data);
 * @method DataService update($id, array $data);
 * @method DataService delete($id);
 * @method DbRepository select(array $fields = ['*']);
 * @method DbRepository with(array $relations);
 * @method DbRepository where($column, $operator = null, $value = null, $boolean = 'and');
 * @method DbRepository cache($duration);
 * @method DbRepository find($id);
 * @method DbRepository first($column, $value);
 * @method DbRepository get($per_page = 20);
 * @method DbRepository getQuery();
 * @method DbRepository search(array $param, $per_page = 20);
 * @method DbRepository paginate($per_page = 20);
 * @method DbRepository customPaginate($total, $per_page = 20);
 */
class SimpleDataService
{
    /**
     * @var Model
     */
    public $model;
    /**
     * @var string
     */
    public $table;
    /**
     * @var DataService
     */
    protected $data;
    /**
     * @var DbRepository
     */
    protected $repo;

    public function __construct()
    {
        $this->data = app(DataService::class);
        $this->repo = app(DbRepository::class);
    }

    public function setModel(Model $model)
    {
        $this->data->setModel($model);
        $this->repo->setModel($model);

        return $this;
    }

    public function __call($method, $parameters)
    {
        if (method_exists($this->data, $method)) {
            return call_user_func_array([$this->data, $method], $parameters);
        }
        if (method_exists($this->repo, $method)) {
            return call_user_func_array([$this->repo, $method], $parameters);
        }

        throw new \Exception('Method "'.$method.'" not found on class "'.class_basename($this->data).'" or "'.class_basename($this->repo).'"');
    }
}
