<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-01-03
 * Time: 21:58
 */
namespace Zwei\Kafka\Tests\Consumer\BroadCast;


use Zwei\Kafka\Consumer\BroadCast\OriginBroadCast;
use Zwei\Kafka\Exceptions\Config\BroadCastConfigException;
use Zwei\Kafka\Tests\TestCase;

class OriginBroadCastTest extends TestCase
{
    use OriginBroadCastProviderTrait;

    /**
     * 测试异常配置
     *
     * @dataProvider configExceptionProvider
     * @expectedException \Zwei\Kafka\Exceptions\Config\BroadCastConfigException
     */
    public function testConfigException($name, array $config)
    {
        $obj = new OriginBroadCast($name, $config);
        $this->assertEquals($name, $obj->getName());
        $this->assertTrue($obj->isEnabled());
        var_dump($obj);
    }

    /**
     * 测试正确配置
     *
     */
    public function testGoodConfig()
    {
        list($name, $config) = $this->goodConfig();
        $obj = new OriginBroadCast($name, $config);
        $this->assertEquals('goodConfig', $obj->getName());
        $this->assertFalse($obj->isEnabled());
        $this->assertEquals([], array_diff($config, $obj->getRawConfig()->all()));
    }
}