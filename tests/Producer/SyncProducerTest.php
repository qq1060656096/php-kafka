<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-22
 * Time: 23:53
 */

namespace Zwei\Kafka\Tests\Producer;

use Zwei\Kafka\Producer\SyncProducer;
use Zwei\Kafka\Tests\TestCase;


/**
 * 同步生产者
 *
 * Class SyncProducerTest
 * @package Zwei\Kafka\Tests\Producer
 */
class SyncProducerTest extends TestCase
{
    use ProducerProviderTrait;
    /**
     * 正常同步生产者
     * @dataProvider getNormal
     * @param string $producerName 生产者名
     * @param string $brokerList 生产者所在集群 broker列表
     * @param array $topicList 生产者主题列表
     * @param array $options 生产者选项
     * @throws \Zwei\Kafka\Exceptions\ProducerConfigException
     */
    public function testNormalSendMessage($producerName, $brokerList, $topicList, $options)
    {
        $obj = new SyncProducer($producerName, $brokerList, $topicList, $options);
        $eventData = [
            'phpunit' => '20181227.141418',
        ];
        $len = $obj->getProducer()->getOutQLen();
        $obj->sendEvent('PHPUNIT_SYNC_TEST', $eventData, ['v0_t_docker_test']);

        $eventData = [
            'phpunit' => '20181227.233018',
        ];
        sleep(1);
        $obj->sendEvent('PHPUNIT_ASYNC_TEST2', $eventData, ['v0_t_docker_test']);
        $len2 = $obj->getProducer()->getOutQLen();
        $this->assertTrue(true);
        $this->assertTrue($len < $len2);

    }


    /**
     * 不存在的topic, 同步生产者
     * @dataProvider getNoExistTopic
     * @param string $producerName 生产者名
     * @param string $brokerList 生产者所在集群 broker列表
     * @param array $topicList 生产者主题列表
     * @param array $options 生产者选项
     * @throws \Zwei\Kafka\Exceptions\ProducerConfigException
     */
    public function testNoExistTopicSendMessage($producerName, $brokerList, $topicList, $options)
    {
        $obj = new SyncProducer($producerName, $brokerList, $topicList, $options);
        $eventData = [
            'phpunit' => '20181227.225035',
            'message' => 'not exist topic phpunit',
            'method' => __METHOD__,
        ];
        $obj->sendEvent('PHPUNIT_SYNC_NO_EXIST_TOPIC_TEST', $eventData, ['v0_t_docker_no_exist_topic']);
        $this->assertTrue(true);
    }

    /**
     * 测试异常kafa borker, 同步生产者
     * @dataProvider getExceptionBrokerConnection
     * @param string $producerName 生产者名
     * @param string $brokerList 生产者所在集群 broker列表
     * @param array $topicList 生产者主题列表
     * @param array $options 生产者选项
     * @throws \Zwei\Kafka\Exceptions\ProducerConfigException
     */
    public function testExceptionBrokerSendMessage($producerName, $brokerList, $topicList, $options)
    {
        $this->markTestSkipped(
            '跳过测试: skip test'.__METHOD__
        );
        $obj = new SyncProducer($producerName, $brokerList, $topicList, $options);
        $eventData = [
            'phpunit' => '20181227.224035',
            'message' => 'exception broker phpunit',
            'method' => __METHOD__,
        ];

        $obj->sendEvent('PHPUNIT_SYNC_EXCEPTION_TEST', $eventData, ['v0_t_exception_broker_topic']);
        $this->assertTrue(true);
    }
}