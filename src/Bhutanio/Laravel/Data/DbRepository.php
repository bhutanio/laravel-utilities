<?php

namespace Bhutanio\Laravel\Data;

use Bhutanio\Laravel\Contracts\Data\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

/**
 * Class DbRepository.
 *
 * @method Model findOrFail($id) find record or fail
 * @method Model firstOrFail($column = 'id', $value) find record or fail
 */
class DbRepository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Model|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected $query;

    /**
     * @var array
     */
    protected $fields;

    /**
     * @var array
     */
    protected $relations;

    /**
     * @var array
     */
    protected $conditions = [];

    /**
     * @var bool|int
     */
    protected $cached = false;

    /**
     * @var string
     */
    protected $cache_id;

    /**
     * @var string
     */
    protected $cache_prefix = 'database:';

    /**
     * @var array
     */
    protected $param;

    /**
     * @var int
     */
    protected $per_page;

    public function __construct()
    {
    }

    /**
     * Set model.
     *
     * @param Model $model
     */
    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Set the columns to be selected.
     *
     * @param array $fields
     *
     * @return self
     */
    public function select(array $fields = ['*'])
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Set relations to the current query.
     *
     * @param array $relations
     *
     * @return self
     */
    public function with(array $relations)
    {
        $this->relations = $relations;

        return $this;
    }

    /**
     * Add a basic where clause to the query.
     *
     * @param string $column
     * @param string $operator
     * @param mixed  $value
     * @param string $boolean
     *
     * @return $this
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        array_push($this->conditions, [
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'boolean' => $boolean,
        ]);

        return $this;
    }

    /**
     * Cache database results, refer to putCache, getCache, getCacheId methods.
     *
     * @param $duration
     *
     * @return self
     */
    public function cache($duration)
    {
        $this->cached = $duration;

        return $this;
    }

    /**
     * Find record by its primary key.
     *
     * @param int $id
     *
     * @return Model
     */
    public function find($id)
    {
        $this->buildQuery();

        return $this->query->find($id);
    }

    /**
     * Get first record by column and value.
     *
     * @param string $column
     * @param mixed  $value
     *
     * @return Model
     */
    public function first($column, $value)
    {
        $this->buildQuery();

        return $this->query->where($column, $value)->first();
    }

    /**
     * Get records from the database.
     *
     * @param int $per_page
     *
     * @return Collection
     */
    public function get($per_page = 20)
    {
        $this->per_page = $per_page;
        $this->buildQuery();

        return $this->query->get();
    }

    /**
     * Get current query builder.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function getQuery()
    {
        $this->buildQuery();

        return $this->query;
    }

    /**
     * Perform advanced search on the database.
     *
     * @param array $param
     * @param int   $per_page
     *
     * @return array [param, title, results]
     */
    public function search(array $param, $per_page = 20)
    {
        $this->per_page = $per_page;

        return [
            'params' => $param,
            'title' => '',
            'results' => [],
        ];
    }

    /**
     * Paginate current results.
     *
     * @param int $per_page
     *
     * @return LengthAwarePaginator
     */
    public function paginate($per_page = 20)
    {
        $this->per_page = $per_page;
        $this->buildQuery();

        return $this->query->paginate($per_page);
    }

    /**
     * Custom Paginate current results, for queries that cannot be paginated using paginate().
     *
     * @param int $total
     * @param int $per_page
     *
     * @return LengthAwarePaginator
     */
    public function customPaginate($total, $per_page = 20)
    {
        $this->per_page = $per_page;
        $this->buildQuery();
        $current_page = Paginator::resolveCurrentPage() ? Paginator::resolveCurrentPage() : 1;
        $data = $this->query->paginate($per_page)->items();
        $pagination = new LengthAwarePaginator($data, $total, $per_page, $current_page, [
            'path' => Paginator::resolveCurrentPath(),
        ]);

        return $pagination;
    }

    public function __call($method, $parameters)
    {
        $this->buildQuery();

        return call_user_func_array([$this->query, $method], $parameters);
    }

    private function buildQuery()
    {
        $this->query = app(get_class($this->model));

        if (!empty($this->fields)) {
            $this->query = $this->query->select($this->fields);
        }
        if (!empty($this->relations)) {
            $this->relations = array_unique($this->relations);
            $this->query = $this->query->with($this->relations);
        }
        if (!empty($this->per_page)) {
            $this->query = $this->query->take($this->per_page);
        }
        if (count($this->conditions)) {
            foreach ($this->conditions as $condition) {
                $this->query = $this->query->where($condition['column'], $condition['operator'], $condition['value'],
                    $condition['boolean']);
            }
        }
    }
}
