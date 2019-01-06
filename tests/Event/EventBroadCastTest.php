<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-01-06
 * Time: 14:09
 */

namespace Zwei\Kafka\Tests\Event;


use Zwei\Kafka\Consumer\BroadCast\BroadCastAbstract;
use Zwei\Kafka\Event\Event;
use Zwei\Kafka\Tests\TestCase;

class EventBroadCastTest extends TestCase
{
    /**
     * 来源广播
     */
    public function testBroadCastOrigin()
    {
        $name = 'v0_b_default_common_origin';
        $obj = Event::getBroadCast($name);
        $this->assertEquals($name, $obj->getName());
    }

    /**
     * 来源广播, 广播来源事件
     * @throws \Zwei\Kafka\Exceptions\Config\BroadCastConfigException
     * @throws \Zwei\Kafka\Exceptions\Consumer\BroadCast\RepeaTBroadCastEventException
     */
    public function testOriginBroadCast()
    {
        $event = <<<str
{
    "id":"20190106141648-0.81973000-0.0.0.0-352-2",
    "eventKey":"EVENT_PRODUCER_ASYNC_COMMON_USER_REGISTER",
    "data":{
        "producerType":"async",
        "user":"phpunit.20181227.235950",
        "pass":"123456",
        "qq":"1060656096",
        "email":"1060656096@qq.com"
    },
    "time":1546755408,
    "ip":"0.0.0.0",
    "additional":{
        "producers":[
            {
                "producer":"v0_p_docker_async_common_user",
                "topic":"v0_t_docker_test",
                "partition":-1
            }
        ]
    }
}
str;
        $event = json_decode($event, true);
        $name = 'v0_b_default_common_origin';
        Event::getBroadCast($name)->broadCastEvent($event, BroadCastAbstract::TYPE_FAIL, 'v0_p_docker_async_common_user', 'v0_t_docker_test');

    }

    
}