<?php
/**
 * Created by PhpStorm.
 * Email: 1060656096@qq.com
 * User: zwei
 * Date: 2018-08-25
 * Time: 16:30
 */

namespace Zwei\Kafka\Helper;

use RdKafka\Producer;
use RdKafka\ProducerTopic;
use RdKafka\Conf;

/**
 * Class ProducerHelper
 * @package Zwei\Kafka\Helper
 */
class ProducerHelper
{
    /**
     * broker列表
     * @var array
     */
    protected $brokerList = null;

    /**
     * 生产者配置
     * @var Conf
     */
    protected $conf = null;

    /**
     * 生产者
     * @var Producer
     */
    protected $producer = null;

    /**
     * 构造方法初始化
     *
     * ProducerHelper constructor.
     * @param array $brokerList 消息服务器列表
     * @param array $options kafka 配置选项
     */
    public function __construct(array $brokerList, array $options = [])
    {
        $this->brokerList = $brokerList;
        $this->conf = ConfHelper::getNewConf($brokerList, null, $options);
    }

    /**
     * 获取生产者
     * @return Producer
     */
    public function getProducer()
    {
        if ($this->producer !== null) {
            return $this->producer;
        }
        $this->producer = $this->getNewProducer();
        return $this->producer;
    }

    /**
     * 获取生产者主题
     *
     * @return ProducerTopic
     */
    public function getProducerTopic()
    {
        return $this->producerTopic;
    }

    /**
     * 获取新的生产者
     * @return Producer
     */
    public function getNewProducer()
    {
        $producer = new Producer($this->conf);
        $producer->setLogLevel(LOG_DEBUG);
        $producer->addBrokers(ConfHelper::brokerListToString($this->brokerList));
        return  $producer;
    }

    /**
     * 获取消费者主题
     * @param Producer $producer 消费者
     * @param string $topic 主题名
     * @return ProducerTopic 消费者主题实例
     */
    public function geProducerNewTopic(Producer $producer, $topic)
    {
        return $producer->newTopic($topic);
    }

}