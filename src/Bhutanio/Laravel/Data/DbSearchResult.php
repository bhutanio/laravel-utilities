<?php

namespace Bhutanio\Laravel\Data;

use Illuminate\Pagination\LengthAwarePaginator;

class DbSearchResult
{
    /**
     * @var array
     */
    public $params;

    /**
     * @var string
     */
    public $title;

    /**
     * @var LengthAwarePaginator
     */
    public $results;

    public function __construct($params, $title, $results)
    {
        array_multisort($params);
        $this->params = $params;
        $this->title = $title;
        $this->results = $results;
    }
}