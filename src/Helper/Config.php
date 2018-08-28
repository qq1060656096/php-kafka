<?php
/**
 * Created by PhpStorm.
 * Email: 1060656096@qq.com
 * User: zwei
 * Date: 2018-08-25
 * Time: 17:56
 */

namespace Zwei\Kafka\Helper;


use Illuminate\Config\Repository;
use Zwei\ComposerVendorDirectory\ComposerVendor;

/**
 * 读取配置文件
 * Class Config
 * @package Zwei\Kafka\Helper
 */
class Config
{
    /**
     * 获取vendor同级config目录下的配置文件
     * @param $file
     * @return mixed
     */
    public static function get($file)
    {
        return include_once $file;
    }

    /**
     * 获取kafka配置
     * @return Repository
     */
    public static function getKafkaConfig()
    {
        static $repository = null;
        if ($repository != null) {
            return $repository;
        }
        $vendorDir = ComposerVendor::getParentDir();
        $obj = new Repository(self::get($vendorDir.'/config/zwei-kafka.php'));
        $repository = $obj;
        return $repository;
    }
}