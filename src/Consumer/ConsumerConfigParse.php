<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-26
 * Time: 19:28
 */
namespace Zwei\Kafka;


use Zwei\Kafka\Exceptions\BaseException;
use Zwei\Kafka\Exceptions\ConfigException;

/**
 * 生产者和消费者通用基类
 *
 * Class CommonBaseAbstract
 * @package Zwei\Kafka
 */
class ConsumerConfigParse
{
    /**
     * 生产者消费者键: 集群名
     */
    const CONFIG__KEY_CLUSTER = 'cluster';

    /**
     * 生产者键: 生产者发送消息类型
     */
    const CONFIG__KEY_CLASS = 'class';

    /**
     * 生产者消费者键: 主题名列表
     */
    const CONFIG_KEY_TOPICS = 'topics';

    /**
     * 生产者消费者键: 选项列表
     */
    const CONFIG_KEY_OPTIONS = 'options';

    /**
     * 生产者配置列表
     * @var array|null
     */
    protected $config = null;

    /**
     * 消费者名
     *
     * @var string|null
     */
    protected $name = null;

    /**
     * ConsumerConfigParse constructor.
     * @param string $name 构造方法
     * @param array $config
     */
    public function __construct($name, array $config)
    {
        $this->name = $name;
        $this->config= $config;
    }


}