<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-21
 * Time: 16:40
 */
namespace Zwei\Kafka\Consumer;

use RdKafka\Conf;
use RdKafka\TopicConf;
use RdKafka\KafkaConsumer;
use Zwei\Kafka\Config\KafkaConfig;

class ConsumerBase
{
    /**
     * kafak broker列表
     * @var string
     */
    protected $brokerList = null;

    /**
     * 生产者配置
     * @var RdKafka\Conf
     */
    protected $conf = null;


    /**
     * 消费者
     * @var KafkaConsumer
     */
    protected $consumer = null;


    /**
     * @var array
     */
    protected $topicList = null;

    /**
     * @var string
     */
    protected $groupId = null;

    /**
     * 构造方法初始化
     *
     * ProducerHelper constructor.
     * @param string $brokerList kafka broker列表
     * @param array $options kafka 配置选项
     */
    public function __construct($brokerList, array $options = [])
    {
        $this->brokerList   = $brokerList;
        $kafkaConfig        = new KafkaConfig($options);
        $this->conf         = $kafkaConfig->getNewConf();
    }

    /**
     * 获取消费者
     *
     * @return KafkaConsumer
     */
    public function getConsumer()
    {
        if ($this->consumer !== null) {
            return $this->consumer;
        }
        $this->consumer = $this->getNewConsumer($this->conf, $this->topicList);
        return $this->consumer;
    }


    /**
     * 获取新的消费者
     *
     * @return KafkaConsumer
     */
    public function getNewConsumer(Conf $conf, array $topicList)
    {
        $consumer = new KafkaConsumer($conf);
        $consumer->subscribe($topicList);
        return  $consumer;
    }

    /**
     *  启用再平衡回调设置为日志分区分配(可选)
     */
    public function enabledRebalanceCb()
    {
        $this->conf->setRebalanceCb(function (KafkaConsumer $kafka, $err, array $partitions = null) {

            $logFormatStr = <<<str
            
[data: %s]:
operation: %s
%s
str;

            switch ($err) {
                case RD_KAFKA_RESP_ERR__ASSIGN_PARTITIONS:
                    echo sprintf($logFormatStr, date('Y-m-d H:i:s'), "Assign", print_r($partitions, true));
                    $kafka->assign($partitions);
                    break;

                case RD_KAFKA_RESP_ERR__REVOKE_PARTITIONS:
                    echo sprintf($logFormatStr, date('Y-m-d H:i:s'), "Revoke", print_r($partitions, true));
                    $kafka->assign(NULL);
                    break;

                default:
                    echo sprintf($logFormatStr, date('Y-m-d H:i:s'), "Exception", $err);
                    throw new \Exception($err);
            }
        });
    }
}