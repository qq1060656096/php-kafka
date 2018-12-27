<?php
require_once dirname(__DIR__).'/vendor/autoload.php';

/**
 * 阿里云kafka 消费者
 */

$file       = \Zwei\ComposerVendorDirectory\ComposerVendor::getParentDir().'/config/test/aliyun-kafka.php';
$config     = \Zwei\Kafka\Helper\CommonConfig::get($file);
$obj        = new \Zwei\Kafka\AppEventConsumer();

$clientId   = $argv[1];
$obj->handle($clientId, $config);

/*
php AliyunAppEventConsumer 消费者客户端id


php examples\AliyunAppEventConsumer.php "consumer_client_id_1"

C:\phpStudy2018\PHPTutorial\php\php-7.1.13-nts\php.exe examples\AliyunAppEventConsumer.php "consumer_client_id_1"
C:\phpStudy2018\PHPTutorial\php\php-7.1.13-nts\php.exe examples\AliyunAppEventConsumer.php "consumer_client_id_2"
 */