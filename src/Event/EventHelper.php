<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-29
 * Time: 23:38
 */

namespace Zwei\Kafka\Event;


class EventHelper
{
    /**
     * 事件key
     */
    const KEY_EVENT_KEY = 'eventKey';

    /**
     * 事件附加信息
     */
    const KEY_ADDITIONAL = 'additional';//  producers

    /**
     * 事件附加信息生产者列表
     */
    const KEY_ADDITIONAL_PRODUCERS = 'producers';


    /**
     * 获取事件来源生产者信息
     *
     * @param array $event 事件
     * @return null|array  null值或者[生产者名, 主题名]
     */
    public static function getEventLastOriginProducer(array $event)
    {
        $producerList = isset($event[self::KEY_ADDITIONAL][self::KEY_ADDITIONAL_PRODUCERS]) ? $event[self::KEY_ADDITIONAL][self::KEY_ADDITIONAL_PRODUCERS] : NULL;
        if (!$producerList) {
            return null;
        }
        $producerInfo = array_pop($producerList);
        return [$producerInfo['producer'], $producerInfo['topic']];
    }

    /**
     * 获取事件id
     * @param string $ip ip地址
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