<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-22
 * Time: 23:29
 */

namespace Zwei\Kafka\Producer;

use RdKafka\Kafka;
use RdKafka\Message;

/**
 * 同步发送kafka 消息
 * Class SyncProducer
 * @package Zwei\Kafka\Producer
 */
class SyncProducer extends ProducerAbstract implements ProducerInsterface
{
    public function sendMessage(array $topicList, $message, $key = null, $partition = RD_KAFKA_PARTITION_UA, $msgflags = 0)
    {
        parent::sendMessage($topicList, $message, $key, $partition, $msgflags); // TODO: Change the autogenerated stub
        $this->getProducer()->poll(0);
    }

    /**
     * @inheritdoc
     */
    public function drMsgCbCallback(Kafka $kafka, Message $message)
    {

    }

    /**
     * @inheritdoc
     */
    public function errorCbCallback($kafka, $err, $reason)
    {
        // TODO: Implement errorCbCallback() method.
    }}