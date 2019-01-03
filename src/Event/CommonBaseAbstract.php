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
abstract class CommonBaseAbstract
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
     * 消费者实例列表
     *
     * @var array|null
     */
    protected $instances = null;

    /**
     * 初始化事件生产者
     *
     * EventProducerConfig constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * 配置name不存在
     *
     * @param string $name
     * @return array
     * @throws ConfigException
     */
    public abstract function getSingleInstanceConfig($name);

    /**
     * 配置name下键不存在
     * @param string $name 配置name
     * @param string $key 键名
     * @return mixed
     * @throws ConfigException
     */
    public abstract function getSingleInstanceConfigKey($name, $key);

    /**
     * 获取新的实例
     *
     * @param string $name
     * @return mixed
     * @throws ConfigException
     */
    public abstract function getNewInstance($name);

    /**
     * 获取实例[没有就创建, 否则就从缓存中获取]
     * @param string $name
     * @return mixed
     * @throws ConfigException
     */
    public function getInstance($name)
    {
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }
        $this->instances[$name] = $this->getNewInstance($name);
        return $this->instances[$name];
    }
}