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
        $lists = [
            [
                'v0_p_docker_common_user', // 生产者名
                ClusterConfig::getValue('docker'),// 集群名
                ProducerConfig::get()->get('v0_p_docker_common_user.topics'),//生产者主题列表
                ProducerConfig::get()->get('v0_p_docker_common_user.options'),//生产者选项列表
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
                'v0_p_docker_no_exist_topic', // 生产者名
                ClusterConfig::getValue('docker'),// 集群名
                ProducerConfig::get()->get('v0_p_docker_no_exist_topic.topics'),//生产者主题列表
                ProducerConfig::get()->get('v0_p_docker_no_exist_topic.options'),//生产者选项列表
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
        $lists = [
            [
                'v0_p_exception_broker', // 生产者名
                ClusterConfig::getValue('exception'),// 集群名
                ProducerConfig::get()->get('v0_p_exception_broker.topics'),//生产者主题列表
                ProducerConfig::get()->get('v0_p_exception_broker.options'),//生产者选项列表
            ],
        ];
        return $lists;
    }


}