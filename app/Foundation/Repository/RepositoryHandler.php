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
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * register all
     */
    public static function registerAll()
    {
        foreach (self::$registerList as $name => $class) {
            self::$repositories[$name] = app($class);
        }
    }

    /**
     * register someone
     *
     * @param $name
     */
    public static function register($name)
    {
        self::$repositories[$name] = app(self::$registerList[$name]);
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
        if (!isset(self::$registerList[$name])) {
            throw new BusinessException($name . ' Unregistered please add to registerList');
        } elseif (!isset(self::$repositories[$name])) {
            self::register($name);
        }

        return self::$repositories[$name];
    }
}
