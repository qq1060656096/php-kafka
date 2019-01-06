<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-01-03
 * Time: 21:58
 */
namespace Zwei\Kafka\Tests\Consumer\BroadCast;


use Zwei\Kafka\Consumer\BroadCast\TargetBroadCast;
use Zwei\Kafka\Exceptions\Config\BroadCastConfigException;
use Zwei\Kafka\Tests\TestCase;

/**
 * 目标广播单元测试
 * Class TargetBroadCastTest
 * @package Zwei\Kafka\Tests\Consumer\BroadCast
 */
class TargetBroadCastTest extends TestCase
{
    use TargetBroadCastProviderTrait;

    /**
     * 测试异常配置
     *
     * @dataProvider configExceptionProvider
     * @expectedException \Zwei\Kafka\Exceptions\Config\BroadCastConfigException
     */
    public function testConfigException($name, array $config)
    {
        $obj = new TargetBroadCast($name, $config);
        $this->assertEquals($name, $obj->getName());
        $this->assertTrue($obj->isEnabled());
    }

    /**
     * 测试正确配置
     *
     */
    public function testGoodConfig()
    {
        list($name, $config) = $this->goodConfig();
        $obj = new TargetBroadCast($name, $config);
        $this->assertEquals('goodConfig', $obj->getName());
        $this->assertTrue($obj->isEnabled());
    }
}