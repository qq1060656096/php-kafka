<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-26
 * Time: 23:14
 */
namespace Zwei\Kafka\Events;


use Zwei\Kafka\Config\ProducerConfig;
use Zwei\Kafka\Producer;
use Zwei\Kafka\Producer\ProducerAbstract;

/**
 * 事件生产者实例
 *
 * Class EventProducer
 * @package Zwei\Kafka\Events
 */
class Event
{
    use InstancesTrait;

    /**
     * 获取生产者实例
     *
     * @param string $name 生产者名
     * @return ProducerAbstract
     * @throws \Zwei\Kafka\Exceptions\ConfigException
     * @throws \Zwei\Kafka\Exceptions\ProducerConfigException
     */
    public static function getProducer($name)
    {
        $instanceName = 'producer';
        /* @var $obj  Producer */
        $obj = static::getInstance($instanceName);
        if ($obj) {
            return $obj->getInstance($name);
        }
        $config     = new ProducerConfig();
        $obj        = new Producer($config->get()->all());
        static::setInstance($instanceName, $obj);
        return $obj->getInstance($name);
    }

    /**
     * 获取消费者实例
     *
     * @param string $name 消费者
     * @return ProducerAbstract
     * @throws \Zwei\Kafka\Exceptions\ConfigException
     * @throws \Zwei\Kafka\Exceptions\ProducerConfigException
     */
    public static function getConsumer($name)
    {
        $instanceName = 'consumer';
        /* @var $obj  Producer */
        $obj = static::getInstance($instanceName);
        if ($obj) {
            return $obj->getInstance($name);
        }
        $config     = new ProducerConfig();
        $obj        = new Producer($config->get()->all());
        static::setInstance($instanceName, $obj);
        return $obj->getInstance($name);
    }
}