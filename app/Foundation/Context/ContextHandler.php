<?php

namespace App\Foundation\Context;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Exceptions\BusinessException;

/**
 * Class ContextHandler
 * @package App\Foundation\Context
 */
class ContextHandler
{
    /**
     * Example of the current class
     * @var $this ;
     */
    protected static $instance;
    /**
     * Data store
     * @var array
     */
    protected static $data = [];

    /**
     * The protected constructor prohibits the creation of an
     * instance of the current class
     *
     * ContextHandler constructor.
     */
    protected function __construct()
    {
    }

    /**
     * The protected clone magic method forbids the current class
     * from being cloned
     */
    protected function __clone()
    {
    }

    /**
     * Returns an instance of the current class
     *
     * @return $this;
     */
    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    /**
     * Set a data to current class
     *
     * @param string $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function set($key, $value)
    {
        if ($value instanceof Closure) {
            $value = $value();
        }

        Arr::set(static::$data, snake_case($key), $value);

        return $value;
    }

    /**
     * Get a data by current class
     *
     * @param string|array $key
     * @param null|mixed $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return Arr::get(static::$data, $key, $default);
    }

    /**
     * Gets part of the specified data from the data set
     *
     * @param array $keys
     *
     * @return array
     */
    public function only($keys)
    {
        return Arr::only(static::$data, $keys);
    }

    /**
     * Determine if there is any data in the data set
     *
     * @param $key
     *
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, static::$data);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * If $data contains the data that needs to be retrieved,
     * return it directly If there is no corresponding
     * access method
     *
     * @param string $key
     *
     * @return mixed|null
     */
    public function __get($key)
    {
        if ($this->has($key)) {
            return $this->get($key);
        }

        $method = 'get' . ucfirst(Str::camel($key)) . 'Attribute';

        if (method_exists($this, $method)) {
            $value = $this->$method();

            return $this->set($key, $value);
        }

        return null;
    }

    /**
     * Gets the subscript of the data set based on the method name
     * Determine whether there is data in the data set
     * Calls private or protected methods when data does not exist
     * And stored in the data set for reuse
     *
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     * @throws \App\Exceptions\BusinessException
     */
    public function __call($method, $parameters)
    {
        $key = Str::snake($method);

        if ($this->has($key)) {
            return $this->get($key);
        }

        if (!method_exists($this, $method)) {
            throw new BusinessException(sprintf('Method %s::%s does not exist.', static::class, $method));
        }

        $value = $this->$method(...$parameters);

        return $this->set($key, $value);
    }

    /**
     * Gets the routing alias for this request
     *
     * @return mixed|string
     */
    protected function getRouteNameAttribute()
    {
        $routeParams = request()->route();
        // TODO $routeParams 在未定义路由是 null
        $routeName = '';

        foreach ($routeParams as $routeParam) {
            if (is_array($routeParam) && array_key_exists('as', $routeParam)) {
                $routeName = $routeParam['as'];
            }
        }

        return $routeName;
    }
}
