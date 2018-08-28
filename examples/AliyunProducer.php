<?php
require_once dirname(__DIR__).'/vendor/autoload.php';

/**
 * 阿里云kafka 发送应用事件消息
 */

$file           = \Zwei\ComposerVendorDirectory\ComposerVendor::getParentDir().'/config/test/aliyun-kafka.php';
$config         = \Zwei\Kafka\Helper\Config::get($file);
$several        = $argv[1];// 发送几次消息
$interval       = $argv[2];// 间隔事件
$topic          = $argv[1];// 主题
$eventJsonStr   = $argv[1];// 事件json字符串
$partition      = $argv[1];// 分区, 默认自动分区
$obj = new \Zwei\Kafka\SendAppEventProducer();
$obj->handle($config, $several, $interval, $topic, $eventJsonStr, $partition = RD_KAFKA_PARTITION_UA);