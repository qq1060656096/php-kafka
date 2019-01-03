<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-21
 * Time: 16:54
 */

namespace Zwei\Kafka\Events\Consumer;


use Zwei\Kafka\CommonBaseAbstract;
use Zwei\Kafka\Exceptions\ConsumerConfigException;
use Zwei\Kafka\Exceptions\ProducerConfigException;
use Zwei\Kafka\Producer\ProducerAbstract;

/**
 * 事件生产者
 *
 * Class Producer
 * @package Zwei\Kafka
 */

/**
 * 事件消费者
 * 
 * Class Consumer
 * @package Zwei\Kafka
 */
class Consumer extends CommonBaseAbstract
{


    /**
     * 消费者键: 消费事件
     */
    const CONFIG_KEY_EVENTS = 'events';

    /**
     * 获取某个消费者配置
     *
     * @param string $name
     * @return array
     * @throws ConsumerConfigException
     */
    public function getSingleInstanceConfig($name)
    {
        if (!isset($this->config[$name])) {
            throw new ConsumerConfigException('consumer name not config');
        }
        return $this->config[$name];
    }

    /**
     * 获取某个消费者配置下某个键的值
     * @param string $name 消费者名
     * @param string $key 消费者键
     * @return mixed
     * @throws ConsumerConfigException
     */
    public function getSingleInstanceConfigKey($name, $key)
    {
        if (!isset($this->config[$name])) {
            $exceptionMsg = sprintf("% consumer not config key %", $name, $key);
            throw new ConsumerConfigException($exceptionMsg);
        }
        return $this->config[$name];
    }

    /**
     * 获取消费者新的实例
     *
     * @param string $name 消费者名
     * @return mixed
     * @throws ConsumerConfigException
     */
    public function getNewInstance($name)
    {
        $cluster        = $this->getSingleInstanceConfigKey($name, self::CONFIG__KEY_CLUSTER);
        $class          = $this->getSingleInstanceConfigKey($name, self::CONFIG__KEY_CLASS);
        $topics         = $this->getSingleInstanceConfigKey($name, self::CONFIG_KEY_TOPICS);
        $options        = $this->getSingleInstanceConfigKey($name, self::CONFIG_KEY_OPTIONS);
        /* @var $class ProducerAbstract */
        return new $class($cluster, $topics, $options);
    }
}