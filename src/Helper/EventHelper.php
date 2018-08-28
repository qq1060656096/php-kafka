<?php
/**
 * Created by PhpStorm.
 * Email: 1060656096@qq.com
 * User: zwei
 * Date: 2018-08-25
 * Time: 16:56
 */

namespace Zwei\Kafka\Helper;

/**
 * 事件
 *
 * Class EventHelper
 * @package Zwei\Kafka\Helper
 */
class EventHelper
{
    /**
     * 获取消费者实例
     * @return RdKafkaProducerHelper|null
     */
    public static function getInstance()
    {
        static $obj = null;
        if ($obj !== null) {
            return $obj;
        }

        $brokerLists = Config::getKafkaConfig()->get('broker_list');
        $kafkaOptions = Config::getKafkaConfig()->get('kafka_options');
        $obj = new ProducerHelper($brokerLists, $kafkaOptions);
        return $obj;
    }

    /**
     * 发送消息到kafka主题
     *
     * @param array $topicList 主题
     * @param string $value 数据
     * @param string|null $key 键
     * @param integer $partition 分区 默认自行分区
     * return array|bool
     */
    public static function sendMessage(array $topicList, $value, $key = null, $partition = RD_KAFKA_PARTITION_UA)
    {
        $producer = self::getInstance()->getProducer();
        foreach ($topicList as $key => $topic) {
            $producerTopic = self::getInstance()->getNewProducerTopic($topic);
            $producerTopic->produce($partition, 0, $value, $key);
        }
    }

    /**
     * 发送事件
     * @param string $eventKey 事件
     * @param array $data 事件数据
     * @param string $ip
     * return null
     */
    public static function sendEvent($eventKey, array $data, $ip = null)
    {
        $ip = $ip === null ? self::getClientIp() : $ip;
        $key        = '';
        $event      = [
            'id'        => self::getEventId($ip),
            'eventKey'  => $eventKey,
            'data'      => $data,
            'time'      => time(),
            'ip'        => $ip,
        ];
        $event  = json_encode($event);
        $topicList  = [config('kafka.zntk-topic')];
        return self::sendMessage($topicList, $event, $key);
    }

    /**
     * 获取事件id
     * @param string $ip
     * @return string
     */
    public static function getEventId($ip = '0.0.0.0')
    {
        static $count;
        $count ++;
        list($usec, ) = explode(" ", microtime());
        $idArr = [
            date('YmdHis'),
            $usec,
            $ip,
            getmypid(),
            $count
        ];
        $id = implode('-', $idArr);
        return $id;
    }

    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    public static function getClientIp($type = 0,$adv=true) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($adv){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }
}