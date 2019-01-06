<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-21
 * Time: 16:07
 */
namespace Zwei\Kafka\Producer;

use RdKafka\Kafka;
use RdKafka\Message;
use RdKafka\Producer;
use RdKafka\ProducerTopic;
use RdKafka\Conf;
use Zwei\Kafka\Config\KafkaConfig;
use Zwei\Kafka\Event\EventHelper;
use Zwei\Kafka\Exceptions\ProducerConfigException;

/**
 * 生产者抽象类
 * Trait ProducerAbstract
 * @package Zwei\Kafka\Producer
 */
class ProducerAbstract implements ProducerInsterface
{
    /**
     * kafak broker列表
     * @var string
     */
    protected $brokerList = null;

    /**
     * 生产者 kafka 主题列表
     * @var array
     */
    protected $topicList = null;

    /**
     * 生产者配置
     * @var RdKafka\Conf
     */
    protected $conf = null;

    /**
     * 生产者名
     *
     * @var string|null
     */
    protected $producerName = null;

    /**
     * 生产者
     * @var Producer
     */
    protected $producer = null;

    /**
     * 生产者主题实例列表
     * @var array
     */
    protected $producerTopic = null;


    /**
     * 构造方法初始化
     *
     * ProducerHelper constructor.
     * @param string $producerName 生产者名
     * @param string $brokerList kafka broker列表
     * @param array $topicList 生成者 kafka 主题列表
     * @param array $options kafka 配置选项
     */
    public function __construct($producerName, $brokerList, $topicList, array $options = [])
    {
        $this->init($producerName, $brokerList, $topicList, $options);
    }

    /**
     * 初始化
     *
     * @param string $producerName 生产者名
     * @param string $brokerList kafka broker列表
     * @param array $topicList 生成者 kafka 主题列表
     * @param array $options kafka 配置选项
     */
    protected final function init($producerName, $brokerList, $topicList, array $options = [])
    {
        $this->producerName = $producerName;
        $this->brokerList   = $brokerList;
        $kafkaConfig        = new KafkaConfig();
        $this->conf         = $kafkaConfig->getNewConf($options);

        $this->setTopicList($topicList);
    }

    /**
     * 获取生产者
     * @return Producer
     */
    public final function getProducer()
    {
        if ($this->producer !== null) {
            return $this->producer;
        }
        $this->producer = $this->getNewProducer();
        return $this->producer;
    }

    /**
     * 获取新的生产者
     * @return Producer
     */
    public final function getNewProducer()
    {
        $producer = new Producer($this->conf);
        $producer->addBrokers($this->brokerList);
        return  $producer;
    }


    /**
     * 获取生产者主题
     * @param string $topic 主题名
     * @return ProducerTopic 消费者主题实例
     */
    public final function geProducerTopic($topic)
    {
        if (isset($this->producerTopic[$topic])) {
            return $this->producerTopic[$topic];
        }
        $this->getProducer();
        $producerTopic = $this->getProducerNewTopic($this->producer, $topic);
        $this->setProducerTopic($topic, $producerTopic);
        return $this->producerTopic[$topic];
    }

    /**
     * 设置生产者主题
     * @param string $topic 主题名
     * @param ProducerTopic $producerTopic
     */
    protected final function setProducerTopic($topic, ProducerTopic $producerTopic)
    {
        $this->producerTopic[$topic] = $producerTopic;
    }
    /**
     * 获取消费者主题
     * @param Producer $producer 消费者
     * @param string $topic 主题名
     * @return ProducerTopic 消费者主题实例
     */
    public final function getProducerNewTopic(Producer $producer, $topic)
    {
        return $producer->newTopic($topic);
    }

    /* ======== 功能函数 start ========== */

    /**
     * 发送单个kafka消息
     * @param array $topicList 主题名列表
     * @param array | string $message 消息
     * @param string|null $key 消息key
     * @param integer $partition 分区: 消息发送到那个分区
     * @param int $msgflags 必须是0
     * @throws ProducerConfigException
     */
    public function sendMessage(array $topicList, $message, $key = null, $partition = RD_KAFKA_PARTITION_UA, $msgflags = 0)
    {
        // 1. 检测发送消息的topic列表是否合法
//        $this->checkTopicList($topicList);
        foreach ($topicList as $key => $topic) {
            if (is_array($message)) {
                $message['additional']['producers'][] = [
                    'producer'  => $this->producerName,
                    'topic'     => $topic,
                    'partition' => $partition,
                ];
                $message = json_encode($message);
            }
            $this->geProducerTopic($topic)->produce($partition, $msgflags, $message, $key);
        }
    }

    /**
     * 发送事件
     *
     * @param string $eventKey 事件名
     * @param array $eventData 事件数据
     * @param array $topicList 主题名列表
     * @param string $ip ip地址
     * @param string|null $key 消息key
     * @param integer $partition 分区: 消息发送到那个分区
     * @param int $msgflags 必须是0
     * @throws ProducerConfigException
     */
    public function sendEvent($eventKey, array $eventData, array $topicList, $ip = null, $key = null, $partition = RD_KAFKA_PARTITION_UA, $msgflags = 0)
    {
        $ip = $ip === null ? EventHelper::getClientIp() : $ip;
        $key        = '';
        $event      = [
            'id'        => EventHelper::getEventId($ip),
            'eventKey'  => $eventKey,
            'data'      => $eventData,
            'time'      => time(),
            'ip'        => $ip,
            'additional' => [],
        ];
        $this->sendMessage($topicList, $event, $key, $partition, $msgflags);
    }



    /**
     * 检测topic是佛合法
     *
     * @param array $topicList
     * @throws ProducerConfigException
     */
    public function checkTopicList(array $topicList)
    {
        foreach ($topicList as $key => $topic) {
            if (!isset($this->topicList[$topic])) {
                $exceptionMsg = sprintf("topic list not found %s topic", $topic);
                throw new ProducerConfigException($exceptionMsg);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function drMsgCbCallback(Kafka $kafka, Message $message)
    {
        // TODO: Implement drMsgCbCallback() method.
    }

    /**
     * @inheritdoc
     */
    public function errorCbCallback($kafka, $err, $reason)
    {
        // TODO: Implement errorCbCallback() method.
    }

    /**
     * 设置 kafka 主题列表
     *
     * @param array $topicList
     */
    protected function setTopicList(array $topicList)
    {
        $this->topicList = array_combine(array_values($topicList), $topicList);
    }

    /* ======== 功能函数 end ========== */

}