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
     * @dataProvider getAsyncNormal
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
        $obj->sendEvent('PHPUNIT_ASYNC_TEST', $eventData, ['v0_t_normal_phpunit']);
        $this->assertTrue(true);
    }
}