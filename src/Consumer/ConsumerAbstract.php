<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-28
 * Time: 18:00
 */

namespace Zwei\Kafka\Consumer;

use Illuminate\Config\Repository;
use RdKafka\Conf;
use RdKafka\TopicConf;
use RdKafka\KafkaConsumer;
use Zwei\Kafka\Config\KafkaConfig;
use Zwei\Kafka\Exceptions\Config\ConsumerConfigException;
use Zwei\Kafka\Exceptions\Consumer\ConsumerEventNotFoundException;

class ConsumerAbstract
{

    /**
     * 消费者键: 集群名
     */
    const CONFIG__KEY_CLUSTER   = 'cluster';

    /**
     * 消费者: 消费者消费类型
     */
    const CONFIG__KEY_CLASS     = 'class';

    /**
     * 消费者键: 主题名列表
     */
    const CONFIG_KEY_TOPICS     = 'topics';

    /**
     * 消费者键: 选项列表
     */
    const CONFIG_KEY_OPTIONS    = 'options';

    /**
     * 消费者键: 消费者超时时间
     */
    const CONFIG_KEY_TIMEOUT_MS = 'timeout-ms';

    /**
     * 消费者键: 客户端列ID表
     */
    const CONFIG_KEY_CLIENT_IDS = 'client-ids';

    /**
     * 消费者键: 转发配置
     */
    const CONFIG_KEY_FORWARD    = 'forward';

    /**
     * 消费者键: 广播配置
     */
    const CONFIG_KEY_BROADCAST  = 'broadcast';

    /**
     * 消费者键: 消费事件列表
     */
    const CONFIG_KEY_EVENTS     = 'events';

    /**
     * 消费者名
     *
     * @var string
     */
    protected $name = null;

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
     * 消费者事件列表
     * @var array
     */
    protected $events = null;

    /**
     * 是否广播
     * @var array|null
     */
    protected $broadcast = null;

    /**
     * 消费者原生配置
     *
     * @var Repository
     */
    protected $rawConfig = null;

    /**
     * 消费者kafka配置
     * @var RdKafka\Conf
     */
    protected $conf = null;

    /**
     * 消费者
     * @var KafkaConsumer
     */
    protected $consumer = null;

    /**
     * 构造方法初始化
     *
     * ProducerHelper constructor.
     * @param string $consumerName 消费者名
     * @param string $brokerList kafka broker列表
     * @param array $topicList 生成者 kafka 主题列表
     * @param array $options kafka 配置选项
     */
    /**
     * 构造方法初始化
     *
     * ConsumerAbstract constructor.
     * @param string $name 消费者名
     * @param string $brokerList broker列表
     * @param array $rawConfig 消费者配置
     */
    public function __construct($name, $brokerList, array $rawConfig)
    {
        $this->init($name, $brokerList, new Repository($rawConfig));
    }



    /**
     * 初始化
     *
     * @param string $name 消费者名
     * @param string $brokerList broker列表
     * @param Repository $rawConfig 消费者配置
     * @throws ConsumerConfigException
     */
    protected final function init($name, $brokerList, Repository $rawConfig)
    {
        $this->rawConfig    = $rawConfig;
        $options            = $this->getRawConfig(self::CONFIG_KEY_OPTIONS);
        $this->name         = $name;
        $this->brokerList   = $brokerList;
        $kafkaConfig        = new KafkaConfig();
        $this->conf         = $kafkaConfig->getNewConf($options);

        $this->setTopicList($this->getRawConfig(self::CONFIG_KEY_TOPICS));
        $this->events       = $this->getRawConfig(self::CONFIG_KEY_EVENTS);

    }


    /**
     * 获取消费者配置
     *
     * @param string $key
     * @return mixed
     * @throws ConsumerConfigException
     */
    public function getRawConfig($key)
    {
        $value = $this->rawConfig->get($key);
        if ($value !== null) {
            return $value;
        }
        throw new ConsumerConfigException(sprintf('consumer "%s" key not config', $key));
    }

    /**
     * 获取kafka消费者
     *
     * @return KafkaConsumer
     */
    public final function getConsumer()
    {
        if ($this->consumer !== null) {
            return $this->consumer;
        }
        $this->consumer = $this->getNewConsumer($this->config, $this->topicList);
        return $this->consumer;
    }


    /**
     * 获取kafka新的消费者
     *
     * @param Conf $config kafka配置
     * @param array $topicList 主题
     * @return KafkaConsumer
     */
    public final function getNewConsumer(Conf $config, array $topicList)
    {
        $consumer = new KafkaConsumer($config);
        $consumer->subscribe($topicList);
        return  $consumer;
    }

    public function handler()
    {
        $timeoutMs  = $this->getRawConfig(self::CONFIG_KEY_TIMEOUT_MS);
        $consumer   = $this->getConsumer();
        while (true) {
            $message = $consumer->consume($timeoutMs);
            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    $rawEventData = $message->payload;
                    // 不是事件格式不处理
                    if (empty($rawEventData)) {
                        break;
                    }
                    var_dump("\n\n var_dump-message-payload:", $message->payload, "\n");
                    $event = json_decode($rawEventData, true);
                    // 不是事件格式不处理
                    if (!is_array($event)) {
                        break;
                    }
                    try {
                        $this->forward();
                        $bool   = $this->executeEvent($event);
                        // 处理成功
                        if ($bool !== false) {
                            $this->success();
                            // 事件广播
                            $this->eventBroadcast($event);
                        } else {// 处理失败
                            $this->fail();
                        }
                    } catch (\Exception $e) {// 异常处理
                        echo sprintf($exceptionFormatStr, date('Y-m-d H:i:s'), $this->clientId, $this->groupId, $event['eventKey'], $event['eventKey'], json_encode($event), $e->getCode(), $e->getMessage(), $e->getTraceAsString());
                        $this->exception();
                    }
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:// 没有消息
//                    echo "No more messages; will wait for more\n";
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:// 超时
//                    echo "Timed out\n";
                    break;
                default:
                    throw new \Exception($message->errstr(), $message->err);
                    break;
            }
        }

    }


    /**
     *
     * 执行事件
     * @param array $event
     * @return mixed
     * @throws ConsumerEventNotFoundException
     */
    protected function executeEvent(array $event) {
        $eventKey = $event['eventKey'];
        // 没有这个事件配置直接成功
        if (!isset($this->events[$eventKey])) {
            throw new ConsumerEventNotFoundException(sprintf('kafka consumer not found event(%s) config', $eventKey));
        }
        $staticFunc = $this->events[$eventKey];
        return call_user_func_array($staticFunc, [$event]);
    }


    /**
     * 是否转发
     *
     * @param array $event 事件
     * @return bool
     * @throws ConsumerConfigException
     */
    public function forward(array $event)
    {
        $forward = $this->getRawConfig(self::CONFIG_KEY_FORWARD);
        if (!$forward) {
            return false;
        }
        return true;
    }

    /**
     * @param array $event
     * @param string $type success -> 成功, fail
     * @param $exception
     * @throws ConsumerConfigException
     */
    public function broadcast(array $event, $type, $exception)
    {
        $broadcast = $this->getRawConfig(self::CONFIG_KEY_BROADCAST);

    }

    protected function setBroadcast(array $b)
    {}

    /**
     * 设置 kafka 主题列表
     *
     * @param array $topicList
     */
    protected function setTopicList(array $topicList)
    {
        $this->topicList = array_combine(array_values($topicList), $topicList);
    }
}