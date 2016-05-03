<?php

namespace Bhutanio\Laravel\Contracts\Data;

interface RepositoryInterface
{
    /**
     * Set the columns to be selected.
     *
     * @param array $fields
     *
     * @return self
     */
    public function select(array $fields = ['*']);

    /**
     * Set relations to the current query.
     *
     * @param array $relations
     *
     * @return self
     */
    public function with(array $relations);

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
    public function where($column, $operator = null, $value = null, $boolean = 'and');

    /**
     * Cache database results, refer to putCache, getCache, getCacheId methods.
     *
     * @param $duration
     *
     * @return self
     */
    public function cache($duration);

    /**
     * Find record by its primary key.
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id);

    /**
     * Get first record by column and value.
     *
     * @param string $column
     * @param mixed  $value
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function first($column, $value);

    /**
     * Get all of the records from the database.
     *
     * @param int $per_page
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get($per_page = 20);

    /**
     * Get current query builder.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getQuery();

    /**
     * Perform advanced search on the database.
     *
     * @param array $param
     * @param int   $per_page
     *
     * @return array [param, title, results]
     */
    public function search(array $param, $per_page = 20);

    /**
     * Paginate current results.
     *
     * @param int $per_page
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate($per_page = 20);

    /**
     * Custom Paginate current results, for queries that cannot be paginated using paginate().
     *
     * @param int $total
     * @param int $per_page
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function customPaginate($total, $per_page = 20);
}
