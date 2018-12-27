<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-22
 * Time: 23:53
 */

namespace Zwei\Kafka\Tests\Producer;

use Zwei\Kafka\Producer\AsyncProducer;
use Zwei\Kafka\Tests\TestCase;

/**
 * 异步生产者
 *
 * Class AsyncProducerTest
 * @package Zwei\Kafka\Tests\Producer
 */
class AsyncProducerTest extends TestCase
{
    use ProducerProviderTrait;

    /**
     * 正常异步生产者
     * @dataProvider getNormal
     * @param string $brokerList
     * @param array $topicList
     * @param array $options
     */
    public function testNomalSendMessage($brokerList, $topicList, $options)
    {
        $obj = new AsyncProducer($brokerList, $topicList, $options);
        $eventData = [
            'phpunit' => '20181227.141418',
        ];
        $len = $obj->getProducer()->getOutQLen();
        $obj->sendEvent('PHPUNIT_ASYNC_TEST', $eventData, ['v0_t_normal_phpunit']);
        $len2 = $obj->getProducer()->getOutQLen();
        $this->assertTrue(true);
        $this->assertTrue($len < $len2);

    }

    /**
     * 测试异常kafa borker, 异步生产者
     * @dataProvider getExceptionBrokerConnection
     * @param string $brokerList
     * @param array $topicList
     * @param array $options
     */
    public function testExceptionBrokerSendMessage($brokerList, $topicList, $options)
    {
        $obj = new AsyncProducer($brokerList, $topicList, $options);
        $eventData = [
            'phpunit' => '20181227.224035',
            'message' => '异常测试',
            'method' => __METHOD__,
        ];
        $obj->sendEvent('PHPUNIT_ASYNC_EXCEPTION_TEST', $eventData, ['v0_t_normal_phpunit']);
        $this->assertTrue(true);
    }

    /**
     * 不存在的topic, 异步生产者
     * @dataProvider getNoExistTopic
     * @param string $brokerList
     * @param array $topicList
     * @param array $options
     */
    public function testNoExistTopicSendMessage($brokerList, $topicList, $options)
    {
        $obj = new AsyncProducer($brokerList, $topicList, $options);
        $eventData = [
            'phpunit' => '20181227.225035',
            'message' => '异常测试',
            'method' => __METHOD__,
        ];
        $obj->sendEvent('PHPUNIT_ASYNC_NO_EXIST_TOPIC_TEST', $eventData, ['v0_t_no_exist_topic_phpunit']);
        $this->assertTrue(true);
    }
}