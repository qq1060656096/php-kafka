<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-29
 * Time: 21:41
 */

namespace Zwei\Kafka\Consumer\Forward;


use Zwei\Kafka\Event;
use Zwei\Kafka\Event\EventHelper;
use Zwei\Kafka\Exceptions\Config\ForwardConfigException;


/**
 * 转发抽象类
 *
 * Class ForwardAbstract
 * @package Zwei\Kafka\Consumer\Forward
 */
abstract class ForwardAbstract
{
    /**
     * 配置键: 是否转发
     */
    const CONFIG_KEY_ENABLED = 'enabled';

    /**
     * 配置键: 广播类型
     */
    const CONFIG_KEY_CLASS = 'class';

    /**
     * 转发名
     * @var string|null
     *
     */
    protected $name = null;

    /**
     * 配置
     * @var Repository|null
     */
    protected $config = null;

    /**
     * 是否转发
     * @var bool
     */
    protected  $enabled = true;

    /**
     * 类
     * @var bool
     */
    protected  $class = null;





    /**
     * BroadCastAbstract constructor.
     * @param string $name
     * @param array $config
     */
    public function __construct($name, array $config)
    {
        $this->name = $name;
        $this->config = new Repository($config);
        $this->init();
    }

    /**
     * 初始化
     */
    protected function init()
    {
        $this->enabled  = $this->getConfig(self::CONFIG_KEY_ENABLED, $this->enabled) ? true : false;
        $this->class    = $this->getConfig(self::CONFIG_KEY_CLASS);
    }

    /**
     * 获取转发配置
     *
     * @param string $key 键
     * @param mixed $defaultValue 默认值
     * @return mixed
     * @throws ForwardConfigException
     */
    public function getConfig($key, $defaultValue = null)
    {
        $value = $this->config->get($key, $defaultValue);
        if ($value !== null) {
            return $value;
        }
        throw new ForwardConfigException(sprintf('forward "%s" key not config', $key));
    }

    /**
     * 是否需要广播
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * 是否需要转发
     * @param array $event
     * @param string $type 广播类型
     * @return bool true|false
     */
    public abstract function handle(array $event, $type);


    /**
     * 广播事件
     * @param array $event
     * @param string $producerName
     * @param string $topicName
     * @return bool
     * @throws BroadCastConfigException
     */
    public function broadCastEvent(array $event, $producerName, $topicName)
    {
        // 1. 检测是否需要转发
        // 2. 检测广播类型是否正确
        // 3. 检测是否所有类型都需要广播
        if (!$this->isEnabled()) {
            return false;
        }

        return true;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * 获取原生配置
     *
     * @return Repository|null
     */
    public function getRawConfig()
    {
        return $this->config;
    }
}