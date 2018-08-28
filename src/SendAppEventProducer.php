<?php
/**
 * Created by PhpStorm.
 * Email: 1060656096@qq.com
 * User: zwei
 * Date: 2018-08-25
 * Time: 22:08
 */

namespace Zwei\Kafka;

use Zwei\Kafka\Helper\ProducerHelper;


/**
 * 发送应用事件生产者
 *
 * Class SendAppEventProducer
 * @package Zwei\Kafka
 */
class SendAppEventProducer
{
    /**
     * 消息服务器列表
     * @var array
     */
    protected $brokerList = null;

    /**
     * 主题
     * @var array
     */
    protected $topic = null;

    /**
     * kaka配置选项
     * @var array
     */
    protected $kafkaOptions = null;


    /**
     * 应用发送消息到kafka
     *
     * @param array $config 配置
     * @param integer $several 发送多少次
     * @param integer $interval 间隔(单位秒)
     * @param string $topic 主题名
     * @param string $eventJsonStr 事件json字符串
     * @param integer $partition 分区(默认自动分配)
     */
    public function handle(array $config, $several, $interval, $topic, $eventJsonStr, $partition = RD_KAFKA_PARTITION_UA)
    {
        $this->kafkaOptions = $config['kafka_options'];
        $this->brokerList   = $config['broker_list'];
        $this->topic        = $topic;
        $obj                = new ProducerHelper($this->brokerList, $this->kafkaOptions);
        $producer           = $obj->getProducer();
        $producerTopic      = $obj->geProducerNewTopic($producer, $this->topic);
        $eventArr           = json_decode($eventJsonStr, true);
        for ($i = 1; $i <= $several; $i ++) {
            $value = json_encode($eventArr);
            print_r($eventArr);
            $producerTopic->produce($partition, 0, $value, $key = null);
            if ($several > 1  && $interval) {
                sleep($interval);
            }
        }
    }
}