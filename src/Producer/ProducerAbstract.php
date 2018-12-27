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
     * @param string $brokerList kafka broker列表
     * @param array $topicList 生成者 kafka 主题列表
     * @param array $options kafka 配置选项
     */
    public function __construct($brokerList, $topicList, array $options = [])
    {
        $this->brokerList   = $brokerList;
        $kafkaConfig        = new KafkaConfig($options);
        $this->conf         = $kafkaConfig->getNewConf();

        $this->setTopicList($topicList);
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
     * 获取新的生产者
     * @return Producer
     */
    public function getNewProducer()
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
    public function geProducerTopic($topic)
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
    protected function setProducerTopic($topic, ProducerTopic $producerTopic)
    {
        $this->producerTopic[$topic] = $producerTopic;
    }
    /**
     * 获取消费者主题
     * @param Producer $producer 消费者
     * @param string $topic 主题名
     * @return ProducerTopic 消费者主题实例
     */
    public function getProducerNewTopic(Producer $producer, $topic)
    {
        return $producer->newTopic($topic);
    }

    /* ======== 功能函数 start ========== */

    /**
     * 发送单个kafka消息
     * @param array $topicList 主题名列表
     * @param string $message 消息
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
        $ip = $ip === null ? self::getClientIp() : $ip;
        $key        = '';
        $event      = [
            'id'        => self::getEventId($ip),
            'eventKey'  => $eventKey,
            'data'      => $eventData,
            'time'      => time(),
            'ip'        => $ip,
        ];
        $message  = json_encode($event);
        $this->sendMessage($topicList, $message, $key, $partition, $msgflags);
    }

    /**
     * 获取事件id
     * @param string $ip ip地址
     * @return string
     */
    public static function getEventId($ip = '0.0.0.0')
    {
        static $count;
        $count ++;
        list($usec, ) = explode(" ", microtime());
        $idArr = [
            date('YmdHis'),
            $usec,
            $ip,
            getmypid(),
            $count
        ];
        $id = implode('-', $idArr);
        return $id;
    }

    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    public static function getClientIp($type = 0,$adv=true) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($adv){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
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