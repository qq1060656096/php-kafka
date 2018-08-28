<?php
/**
 * Created by PhpStorm.
 * Email: 1060656096@qq.com
 * User: zwei
 * Date: 2018-08-25
 * Time: 16:30
 */

namespace Zwei\Kafka\Helper;

use RdKafka\Conf;
use RdKafka\TopicConf;
use RdKafka\KafkaConsumer;

/**
 * Class ConsumerHelper
 * @package Zwei\Kafka\Helper
 */
class ConsumerHelper
{
    /**
     * kafka消费者配置
     * @var Conf
     */
    protected $conf = null;

    /**
     * 主题配置
     * @var TopicConf
     */
    protected $topicConf = null;
    /**
     * 消费者
     * @var KafkaConsumer
     */
    protected $consumer = null;

    /**
     * @var array
     */
    protected $brokerList = null;

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
     * KafkaConsumerHelper constructor.
     * @param array $brokerList broker列表 ['127.0.0.1:9192', '127.0.0.2:9192']
     * @param array $topicList 主题列表
     * @param string $groupId 分组
     * @param array $options kafka 配置选项
     */
    public function __construct(array $brokerList, array $topicList, $groupId, array $options = [])
    {
        $this->brokerList   = $brokerList;
        $this->topicList    = $topicList;
        $this->groupId      = $groupId;
        $this->conf = ConfHelper::getNewConf($brokerList, $groupId, $options);
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
     * @param Conf $config kafka配置
     * @param array $topicList 主题
     * @return KafkaConsumer
     */
    public function getNewConsumer(Conf $conf, array $topicList)
    {
        $consumer = new KafkaConsumer($conf);
        $consumer->subscribe($topicList);
        return  $consumer;
    }
}