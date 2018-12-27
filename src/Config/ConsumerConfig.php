<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-26
 * Time: 22:55
 */

namespace Zwei\Kafka\Config;



/**
 * 获取消费者配置
 *
 * Class ConsumerConfig
 * @package Zwei\Kafka\Config
 */
class ConsumerConfig
{
    public static function get()
    {
        return CommonConfig::getCache('consumer', 'zwei-kafka-consumer.php');
    }
}