<?php

namespace Bhutanio\Laravel\Contracts\Services;

interface FilerInterface
{
    /**
     * @param string $type
     *
     * @return self
     */
    public function type($type);

    /**
     * @param string $file
     *
     * @return string
     */
    public function path($file);

    /**
     * @param string $file
     *
     * @return mixed
     */
    public function size($file);

    /**
     * @param $file
     *
     * @return mixed
     */
    public function has($file);

    /**
     * @param $file
     *
     * @return mixed
     */
    public function get($file);

    /**
     * @param $file
     * @param $contents
     *
     * @return bool
     */
    public function put($file, $contents);

    /**
     * @param $source
     * @param $destination
     *
     * @return bool
     */
    public function copy($source, $destination);

    /**
     * @param $source
     * @param $destination
     *
     * @return bool
     */
    public function move($source, $destination);

    /**
     * @param $file
     *
     * @return mixed
     */
    public function delete($file);

    /**
     * @param $file
     *
     * @return bool
     */
    public function sync($file);
}
