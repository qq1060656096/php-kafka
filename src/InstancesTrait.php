<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-27
 * Time: 10:07
 */

namespace Zwei\Kafka\Events;

/**
 * Trait InstancesTrait
 * @package Zwei\Kafka\Events
 */
trait InstancesTrait
{
    /**
     *
     * @var array | null
     */
    private  static $staticInstances = null;

    /**
     * 获取实例[没有就创建, 否则就从缓存中获取]
     * @param string $name
     * @return mixed
     */
    public static function getInstance($name)
    {
        if (isset(static::$staticInstances[$name])) {
            return static::$staticInstances[$name];
        }
        return null;
    }

    /**
     * 设置实例
     *
     * @param string $name
     * @param mixed $instance
     */
    private static function setInstance($name, $instance)
    {
        static::$staticInstances[$name] = $instance;
    }
}