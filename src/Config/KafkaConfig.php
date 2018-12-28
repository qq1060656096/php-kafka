<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-19
 * Time: 11:42
 */

namespace Zwei\Kafka\Config;

use RdKafka\Conf;

class KafkaConfig
{
    /**
     * 获取kafka配置
     * @param array $options
     * @return Conf
     */
    public function getNewConf(array $options = [])
    {
        $conf = new Conf();
        $conf = $this->setConf($conf, $options);
        $conf->setErrorCb(function ($kafka, $err, $reason) {
            printf("Kafka error: %s (reason: %s)\n", rd_kafka_err2str($err), $reason);
            var_dump($kafka, $err, $reason);
exit;
        });
        $conf->setErrorCb(function ($kafka, $err, $reason) {
            printf("Kafka error: %s (reason: %s)\n", rd_kafka_err2str($err), $reason);
            var_dump($kafka, $err, $reason);
            exit;
        });

        $conf->setDrMsgCb(function ($kafka, $message) {
            if ($message->err) {
                // message permanently failed to be delivered
                print_r($kafka);
                print_r($message);
            } else {
                // message successfully delivered
            }


        });
        return $conf;
    }

    /**
     * 设置kafka配置
     * @param Conf $conf 配置实例
     * @param array $options 选项键值数组
     * @return Conf
     */
    public function setConf(Conf $conf, array $options)
    {
        foreach ($options as $key => $value) {
            $conf->set($key, $value);
        }
        return $conf;
    }
}