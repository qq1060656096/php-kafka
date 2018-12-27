<?php
/**
 * Created by PhpStorm.
 * Email: 1060656096@qq.com
 * User: zwei
 * Date: 2018-08-25
 * Time: 17:56
 */

namespace Zwei\Kafka\Config;


use Illuminate\Config\Repository;
use Zwei\ComposerVendorDirectory\ComposerVendor;
use Zwei\Kafka\Exceptions\ConfigException;

/**
 * 读取配置文件
 * Class CommonConfig
 * @package Zwei\Kafka\Config
 */
class CommonConfig
{
    /**
     * 配置列表
     * @var array|null
     */
    protected $config = null;

    /**
     * 加载配置文件的配置
     * @param string $filePath 文件路径(全路径,或者相对路径)
     * @return array
     * @throws ConfigException
     */
    public function load($filePath)
    {
        $vendorDir  = ComposerVendor::getParentDir();
        $filePath   = file_exists($filePath) ? $filePath : $vendorDir.'/config/'.$filePath;
        if (!file_exists($filePath)) {
           throw new ConfigException(sprintf('%s config file not found', $filePath));
        }
        return include $filePath;
    }

    /**
     * 获取某个别名配置(没有就获取配置, 否则从已有的别名中获取)
     * @param string $name 别名
     * @param string $filePath 文件路径
     * @return Repository
     * @throws ConfigException
     */
    public function get($name, $filePath)
    {
        if (isset($this->config[$name])) {
            return $this->config[$name];
        }

        $repository = new Repository($this->load($filePath));
        $this->config[$name] = $repository;
        return $repository;
    }

    /**
     * 从缓存中获取某个别名配置(没有就获取配置, 否则从已有的别名中获取)
     * @param string $name 别名
     * @param string $filePath 文件路径
     * @return Repository
     * @throws ConfigException
     */
    public static function getCache($name, $filePath)
    {
        /**
         * @var $obj CommonConfig
         */
        static $obj = null;
        if ($obj) {
            return $obj->get($name, $filePath);
        }

        $obj = new CommonConfig();
        return $obj->get($name, $filePath);
    }

    /**
     * 获取集群配置配置
     * @return Repository
     */
    public static function getClusterConfig()
    {
        static $repository = null;
        if ($repository != null) {
            return $repository;
        }
        $vendorDir = ComposerVendor::getParentDir();
        $obj = new Repository(self::get($vendorDir.'/config/zwei-kafka-cluster.php'));
        $repository = $obj;
        return $repository;
    }

    /**
     * 获取生产者配置配置
     * @return Repository
     */
    public static function getProducerConfig()
    {
        static $repository = null;
        if ($repository != null) {
            return $repository;
        }
        $vendorDir = ComposerVendor::getParentDir();
        $obj = new Repository(self::get($vendorDir.'/config/zwei-kafka-producer.php'));
        $repository = $obj;
        return $repository;
    }

    /**
     * 获取消费者配置
     * @return Repository
     */
    public static function getConsumerConfig()
    {
        static $repository = null;
        if ($repository != null) {
            return $repository;
        }
        $vendorDir = ComposerVendor::getParentDir();
        $obj = new Repository(self::get($vendorDir.'/config/zwei-kafka-consumer.php'));
        $repository = $obj;
        return $repository;
    }
}