<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-27
 * Time: 23:51
 */
namespace Zwei\Kafka\Tests;

use Zwei\Kafka\Event;

/**
 * 测试事件
 * Class EventTest
 * @package Zwei\Kafka\Tests
 */
class EventProducerTest extends TestCase
{
    /**
     * 测试同步生产者
     * @throws \Zwei\Kafka\Exceptions\ConfigException
     * @throws \Zwei\Kafka\Exceptions\ProducerConfigException
     */
    public function testSyncSendEvent()
    {
        // 用户注册
        $eventData = [
            'producerType' => 'sync',
            'user'  => 'phpunit.20181227.235950',
            'pass'  => '123456',
            'qq'    => '1060656096',
            'email' => '1060656096@qq.com',
        ];
        Event::getProducer('v0_p_docker_sync_common_user')
            ->sendEvent('EVENT_PRODUCER_SYNC_COMMON_USER_REGISTER', $eventData, ['v0_t_docker_test']);
        $this->assertTrue(true);
    }

    /**
     * 测试异步生产者
     * @throws \Zwei\Kafka\Exceptions\ConfigException
     * @throws \Zwei\Kafka\Exceptions\ProducerConfigException
     */
    public function testAsyncSendEvent()
    {
        // 用户注册
        $eventData = [
            'producerType' => 'async',
            'user'  => 'phpunit.20181227.235950',
            'pass'  => '123456',
            'qq'    => '1060656096',
            'email' => '1060656096@qq.com',
        ];
        Event::getProducer('v0_p_docker_async_common_user')
            ->sendEvent('EVENT_PRODUCER_ASYNC_COMMON_USER_REGISTER', $eventData, ['v0_t_docker_test']);
        $this->assertTrue(true);
    }
}