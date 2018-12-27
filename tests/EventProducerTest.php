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
    public function testAsyncSendEvent()
    {
        // 用户注册
        $eventData = [
            'user'  => 'phpunit.20181227.235950',
            'pass'  => '123456',
            'qq'    => '1060656096',
            'email' => '1060656096@qq.com',
        ];
        Event::getProducer('v0_p_default_common_user_register')
            ->sendEvent('COMMON_USER_REGISTER', $eventData, ['test']);


        Event::getProducer('v0_p_default2_common')
            ->sendEvent('COMMON_USER_REGISTER2', $eventData, ['v0_t_default_test2']);
        $this->assertTrue(true);
    }
}