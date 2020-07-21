<?php

namespace App\Foundation\Service;

use App\Exceptions\BusinessException;

class ServiceHandle
{
    protected static $instance;
    protected static $pockets;
    protected static $registerList;

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    /**
     * get singleton
     *
     * @return $this
     */
    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * register all
     */
    public static function registerAll()
    {
        foreach (static::$registerList as $name => $class) {
            static::$pockets[$name] = app($class);
        }
    }

    /**
     * register someone
     *
     * @param $name
     */
    public static function register($name)
    {
        static::$pockets[$name] = app(static::$registerList[$name]);
    }

    /**
     * @param $name
     *
     * @return mixed
     * @throws BusinessException
     */
    public function __get($name)
    {
        if (isset(static::$registerList[$name]) && !isset(static::$pockets[$name])) {
            static::register($name);
        } elseif (!isset(static::$pockets[$name])) {
            throw new BusinessException($name . ' Unregistered please add to registerList');
        }

        return static::$pockets[$name];
    }
}
