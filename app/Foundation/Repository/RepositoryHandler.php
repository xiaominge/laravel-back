<?php

namespace App\Foundation\Repository;

use App\Exceptions\BusinessException;

class RepositoryHandler
{
    /**
     * @var object
     */
    protected static $instance;
    /**
     * @var mixed
     */
    protected static $registerList;

    /**
     * All repository sets
     * @var mixed
     */
    protected static $repositories = [];

    /**
     * The protected constructor prohibits the creation of an
     * instance of the current class
     *
     * RepositoryHandler constructor.
     */
    public function __construct()
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
     * Returns an instance of the RepositoryHandler class
     *
     * @return  $this
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
            static::$repositories[$name] = app($class);
        }
    }

    /**
     * register someone
     *
     * @param $name
     */
    public static function register($name)
    {
        static::$repositories[$name] = app(static::$registerList[$name]);
    }

    /**
     * Get the corresponding repository object according to different repository names
     *
     * @param $name
     *
     * @return mixed
     * @throws BusinessException
     */
    public function __get($name)
    {
        if (!isset(static::$registerList[$name])) {
            throw new BusinessException($name . ' Unregistered please add to registerList');
        } elseif (!isset(static::$repositories[$name])) {
            static::register($name);
        }

        return static::$repositories[$name];
    }
}
