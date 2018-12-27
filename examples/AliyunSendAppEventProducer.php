<?php
require_once dirname(__DIR__).'/vendor/autoload.php';

print_r($argv);
/**
 * 阿里云kafka 生产者
 */

$file           = \Zwei\ComposerVendorDirectory\ComposerVendor::getParentDir().'/config/test/aliyun-kafka.php';
$config         = \Zwei\Kafka\Helper\CommonConfig::get($file);
$several        = $argv[1];// 发送几次消息
$interval       = $argv[2];// 间隔事件
$topic          = $argv[3];// 主题
$eventJsonStr   = $argv[4];// 事件json字符串
$partition      = isset($argv[5]) ? $argv[5] : RD_KAFKA_PARTITION_UA;// 分区, 默认自动分区
$obj = new \Zwei\Kafka\SendAppEventProducer();
$obj->handle($config, $several, $interval, $topic, $eventJsonStr, $partition);

/*
php AliyunSendAppEventProducer.php 发送次数 间隔时间  主题名 事件json字符串 分区[默认自动分区]


# 发送10次,每次间隔1秒,发送到"test_topic"主题, 事件内容是 "{}", 发送到默认自动分区
php AliyunSendAppEventProducer.php "10" "1"  "test_topic" "{}"

# 发送10次,每次间隔1秒,发送到"test_topic"主题, 事件内容是 "{}", 发送到1分区
php AliyunSendAppEventProducer.php "10" "1"  "test_topic" "{}" "1"

C:\phpStudy2018\PHPTutorial\php\php-7.1.13-nts\php.exe examples\AliyunSendAppEventProducer.php "10" "1"  "test10" "{\"id\":3389,\"eventKey\":\"EVENT_INIT\",\"name\":\"test\"}" "0"

C:\phpStudy2018\PHPTutorial\php\php-7.1.13-nts\php.exe examples\AliyunSendAppEventProducer.php "10" "2"  "test6" "{\"id\":1,\"eventKey\":\"EVENT_INIT\",\"name\":\"1111111111\"}" "1"

C:\phpStudy2018\PHPTutorial\php\php-7.1.13-nts\php.exe examples\AliyunSendAppEventProducer.php "10" "2"  "test6" "{\"id\":3,\"eventKey\":\"EVENT_INIT\",\"name\":\"8888\"}" "3"
 */