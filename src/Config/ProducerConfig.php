<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-26
 * Time: 22:55
 */

namespace Zwei\Kafka\Config;



/**
 * 获取生产者配置
 *
 * Class ProducerConfig
 * @package Zwei\Kafka\Config
 */
class ProducerConfig
{

    /**
     * @return \Illuminate\Config\Repository
     * @throws \Zwei\Kafka\Exceptions\ConfigException
     */
    public static function get()
    {
        return CommonConfig::getCache('producer', 'zwei-kafka-producer.php');
    }
}