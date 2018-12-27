<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-22
 * Time: 23:33
 */

namespace Zwei\Kafka\Producer;

use RdKafka\Kafka;
use RdKafka\Message;

interface ProducerInsterface
{
    /**
     * 发送单个kafka消息
     * @param array $topicList 主题名列表
     * @param string $message 消息
     * @param string|null $key 消息key
     * @param integer $partition 分区: 消息发送到那个分区
     * @param int $msgflags 必须是0
     * @throws ProducerConfigException
     */
    public function sendMessage(array $topicList, $message, $key = null, $partition = RD_KAFKA_PARTITION_UA, $msgflags = 0);

    /**
     * 生产者发送回调
     *
     * @param Kafka $kafka
     * @param Message $message
     * @return mixed
     */
    public function drMsgCbCallback(Kafka $kafka, Message $message);

    /**
     * 生产者错误回调
     *
     * @param object $kafka
     * @param int $err
     * @param string $reason
     * @return mixed
     */
    public function errorCbCallback($kafka, $err, $reason);

}