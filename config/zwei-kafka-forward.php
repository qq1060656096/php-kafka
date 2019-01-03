<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-14
 * Time: 11:37
 */
return [
    'v0_f_default_common_' => [// 广播id broadcase id: 必须唯一
        'class'     => \Zwei\Kafka\Consumer\Forward\CommonForward::class,// 广播类型: 事件广播到目标生产者主题, 即事件广播的某个生产者的某个主题
        'enabled'   => true, // 是否转发: true -> 转发, false -> 不转发
        'producer'  => '', // 生产者名,请看"zwei-kafka-producer.php"配置文件
        'topic'     => '', // 主题名
    ],
];