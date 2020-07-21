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
            self::$pockets[$name] = app($class);
        }
    }

    /**
     * register someone
     *
     * @param $name
     */
    public static function register($name)
    {
        self::$pockets[$name] = app(self::$registerList[$name]);
    }

    /**
     * @param $name
     *
     * @return mixed
     * @throws BusinessException
     */
    public function __get($name)
    {
        if (isset(self::$registerList[$name]) && !isset(self::$pockets[$name])) {
            self::register($name);
        } elseif (!isset(self::$pockets[$name])) {
            throw new BusinessException($name . ' Unregistered please add to registerList');
        }

        return self::$pockets[$name];
    }
}
