<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-26
 * Time: 22:55
 */

namespace Zwei\Kafka\Config;

use Zwei\Kafka\Exceptions\ClusterConfigException;

/**
 * 获取集群配置
 *
 * Class ClusterConfig
 * @package Zwei\Kafka\Config
 */
class ClusterConfig
{
    /**
     *
     * @return \Illuminate\Config\Repository
     * @throws \Zwei\Kafka\Exceptions\ConfigException
     */
    public static function get()
    {
        return CommonConfig::getCache('cluster', 'zwei-kafka-cluster.php');
    }

    /**
     * 获取集群配置
     *
     * @param string $name 集群名
     * @return string
     * @throws ClusterConfigException
     * @throws \Zwei\Kafka\Exceptions\ConfigException
     */
    public static function getValue($name)
    {
       $value = self::get()->get($name);
       if (!$value) {
           $exceptionMsg = sprintf("% cluster config not fund", $name);
           throw new ClusterConfigException($exceptionMsg);
       }
       is_array($value) ? null : $value = [$value];
       return implode(',', $value);
    }
}