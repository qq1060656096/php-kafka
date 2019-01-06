<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-29
 * Time: 21:41
 */

namespace Zwei\Kafka\Consumer\BroadCast;


use Illuminate\Config\Repository;
use Zwei\Kafka\Event\Event;
use Zwei\Kafka\Event\EventHelper;
use Zwei\Kafka\Exceptions\Config\BroadCastConfigException;
use Zwei\Kafka\Exceptions\Consumer\BroadCast\RepeaTBroadCastEventException;

/**
 * 广播抽象类
 *
 * Class BroadCastAbstract
 * @package Zwei\Kafka\Consumer\BroadCast
 */
abstract class BroadCastAbstract
{
    /**
     * 广播配置键: 是否转发
     */
    const CONFIG_KEY_ENABLED = 'enabled';

    /**
     * 广播配置键: 广播类型
     */
    const CONFIG_KEY_CLASS = 'class';

    /**
     * 广播配置键: 广播类型
     */
    const CONFIG_KEY_TYPE = 'type';




    /* ===== 广播类型 start ==== */
    /**
     * 所有都需要广播
     */
    const TYPE_ALL      = 'all';

    /**
     * 执行成功才广播
     */
    const TYPE_SUCCESS  = 'success';

    /**
     * 执行成功才广播
     */
    const TYPE_FAIL     = 'fail';

    /**
     * 执行异常才广播
     */
    const TYPE_EXCEPTION = 'exception';
    /* ===== 广播方式 end ==== */

    /**
     * 广播名
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
     * 是否广播
     * @var bool
     */
    protected  $enabled = true;

    /**
     * 广播类
     * @var bool
     */
    protected  $class = null;

    /**
     * 广播类型
     *
     * @var array
     */
    protected $type = null;



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
        $this->setType($this->getConfig(self::CONFIG_KEY_TYPE));
    }

    /**
     * 获取转发配置
     *
     * @param string $key 键
     * @param mixed $defaultValue 默认值
     * @return mixed
     * @throws BroadCastConfigException
     */
    public function getConfig($key, $defaultValue = null)
    {
        $value = $this->config->get($key, $defaultValue);
        if ($value !== null && $value !== '') {
            return $value;
        }
        throw new BroadCastConfigException(sprintf('broadcast "%s" key not config', $key));
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
     * 获取广播key
     * @param array $event
     * @param string $type
     * @return string
     */
    public function getBroadCastEventKey(array $event, $type)
    {
        // 广播后缀
        $broadCastSuffix = strtoupper($type);
        $eventKey = $event[EventHelper::KEY_EVENT_KEY];
        $arr = explode($broadCastSuffix, $eventKey);
        $broadCastEventKey = $arr[0].'_'.$broadCastSuffix;
        return $broadCastEventKey;
    }

    /**
     * 广播事件
     * @param array $event
     * @param string $type
     * @param string $producerName
     * @param string $topicName
     * @return bool
     * @throws BroadCastConfigException
     * @throws RepeaTBroadCastEventException
     */
    public function broadCastEvent(array $event, $type, $producerName, $topicName)
    {
        // 1. 检测是否需要广播
        // 2. 检测广播类型是否正确
        // 3. 检测是否所有类型都需要广播
        if (!$this->isEnabled()) {
            return false;
        }
        $typesArr = $this->getAllType();
        unset($typesArr[self::TYPE_ALL]);
        if (!in_array($type, $typesArr)) {
            throw new BroadCastConfigException(sprintf('broadcast type config must in (%s) list', implode(',', $this->getAllType())));
        }
        $broadCastEventKey = $this->getBroadCastEventKey($event, $type);
        $eventKey = $event[EventHelper::KEY_EVENT_KEY];
        // 广播事件key和和当前事件key一致就不在广播
        if ($eventKey === $broadCastEventKey) {
            throw new RepeaTBroadCastEventException(sprintf('broadcast Repeated broadcast event key(%s)',  $eventKey));
        }
        $event[EventHelper::KEY_EVENT_KEY] = $broadCastEventKey;
        // 广播事件
        Event::getProducer($producerName)->sendMessage([$topicName], $event);
        return true;
    }

    /**
     * 设置广播类型
     *
     * @param array $types
     * @throws BroadCastConfigException
     */
    protected final function setType(array $types)
    {
        if (empty($types)) {
            throw new BroadCastConfigException(sprintf('broadcast type key value config must in (%s) list', implode(',', $this->getAllType())));
        }
        $allTypes = $this->getAllType();
        foreach ($types as $rowType) {
            if (!isset($allTypes[$rowType])) {
                throw new BroadCastConfigException(sprintf('broadcast type key  value config must in (%s) list', implode(',', $this->getAllType())));
            }
        }
        $this->type = array_combine(array_keys($types), array_values($types));

    }

    /**
     * 获取所有广播类型
     * @return array
     */
    public final function getAllType()
    {
        return [
            self::TYPE_ALL          => self::TYPE_ALL,
            self::TYPE_SUCCESS      => self::TYPE_SUCCESS,
            self::TYPE_FAIL         => self::TYPE_FAIL,
            self::TYPE_EXCEPTION    => self::TYPE_EXCEPTION,
        ];
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