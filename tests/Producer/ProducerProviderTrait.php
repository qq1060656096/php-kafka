<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-27
 * Time: 11:49
 */

namespace Zwei\Kafka\Tests\Producer;


use Zwei\Kafka\CommonBaseAbstract;
use Zwei\Kafka\Config\ClusterConfig;
use Zwei\Kafka\Config\ProducerConfig;

trait ProducerProviderTrait
{
    /**
     * 正常生产者数据提供者
     *
     * @return array
     * @throws \Zwei\Kafka\Exceptions\ClusterConfigException
     * @throws \Zwei\Kafka\Exceptions\ConfigException
     */
    public function getNormal()
    {
//        echo 123;exit;
        $lists = [
            [
                ClusterConfig::getValue('normal'),// 集群名
                ProducerConfig::get()->get('v0_p_default_phpunit_async_normal.topics'),//生产者主题列表
                ProducerConfig::get()->get('v0_p_default_phpunit_async_normal.options'),//生产者选项列表
            ],
        ];
        return $lists;
    }

    /**
     * 正常生产者数据提供者
     *
     * @return array
     * @throws \Zwei\Kafka\Exceptions\ClusterConfigException
     * @throws \Zwei\Kafka\Exceptions\ConfigException
     */
    public function getExceptionBrokerConnection()
    {
//        echo 123;exit;
        $lists = [
            [
                ClusterConfig::getValue('exception'),// 集群名
                ProducerConfig::get()->get('v0_p_default_phpunit_async_exception.topics'),//生产者主题列表
                ProducerConfig::get()->get('v0_p_default_phpunit_async_exception.options'),//生产者选项列表
            ],
        ];
        return $lists;
    }

    /**
     * 不存在的topic生产者数据提供者
     *
     * @return array
     * @throws \Zwei\Kafka\Exceptions\ClusterConfigException
     * @throws \Zwei\Kafka\Exceptions\ConfigException
     */
    public function getNoExistTopic()
    {
        $lists = [
            [
                ClusterConfig::getValue('normal'),// 集群名
                ProducerConfig::get()->get('v0_p_default_phpunit_async_no_exist_topic.topics'),//生产者主题列表
                ProducerConfig::get()->get('v0_p_default_phpunit_async_no_exist_topic.options'),//生产者选项列表
            ],
        ];
        return $lists;
    }
}