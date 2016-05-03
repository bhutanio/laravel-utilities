<?php

namespace Bhutanio\Laravel\Contracts\Services;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

interface HttpClientInterface extends Arrayable, Jsonable
{
    /**
     * Set URL for HTTP requests.
     *
     * @param string $url
     *
     * @return self
     */
    public function setUrl($url);

    /**
     * Set basic HTTP authentication.
     *
     * @param $username string
     * @param $password string
     *
     * @return self
     */
    public function setAuth($username, $password);

    /**
     * Return response headers.
     *
     * @return array
     */
    public function getHeader();

    /**
     * Return response body.
     *
     * @return string
     */
    public function getBody();

    /**
     * Cache response keyed by url.
     *
     * @param $duration
     * @param $cache_id
     *
     * @return self
     */
    public function cache($duration, $cache_id = null);

    /**
     * Perform a request.
     *
     * @param string $method
     * @param array  $options
     *
     * @return self
     */
    public function request($method, array $options = []);

    /**
     * Perform get request.
     *
     * @param array $options
     *
     * @return self
     */
    public function get(array $options = []);

    /**
     * Perform post request.
     *
     * @param array $options
     *
     * @return self
     */
    public function post(array $options = []);

    /**
     * Attach query to the request.
     *
     * @param array $query
     *
     * @return self
     */
    public function withQuery(array $query);

    /**
     * Attach data stream to the request.
     *
     * @param $body
     *
     * @return self
     */
    public function withBody($body);

    /**
     * Attach form data to the request.
     *
     * @param array $form ['field_name'=>'value', ...]
     *
     * @return self
     */
    public function withForm(array $form);

    /**
     * Attach multipart form data to the request.
     *
     * @param array $form [[name, contents, filename], ...]
     *
     * @return self
     */
    public function withMultiForm(array $form);

    /**
     * Attach file as multipart form data to the request.
     *
     * @param array $file [name, contents, filename]
     *
     * @return self
     */
    public function withFile(array $file);
}
