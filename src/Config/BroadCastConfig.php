<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-26
 * Time: 22:55
 */

namespace Zwei\Kafka\Config;



use Zwei\Kafka\Exceptions\Consumer\BroadCast\BroadCastConfigException;

/**
 * 获取生产者配置
 *
 * Class ProducerConfig
 * @package Zwei\Kafka\Config
 */
class BroadCastConfig
{

    /**
     * 获取配置
     *
     * @return \Illuminate\Config\Repository
     * @throws \Zwei\Kafka\Exceptions\ConfigException
     */
    public static function get()
    {
        return CommonConfig::getCache('broadcast', 'zwei-kafka-broadcast.php');
    }


    /**
     * 获取广播配置
     * @param $name
     * @return string
     * @throws BroadCastConfigException
     */
    public static function getValue($name)
    {
        $value = self::get()->get($name);
        if (!$value) {
            $exceptionMsg = sprintf("% broadcast config not fund", $name);
            throw new BroadCastConfigException($exceptionMsg);
        }
        is_array($value) ? null : $value = [$value];
        return implode(',', $value);
    }
}