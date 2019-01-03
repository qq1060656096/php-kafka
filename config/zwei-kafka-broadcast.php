<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-14
 * Time: 11:37
 */
return [
    'v0_b_default_common_origin' => [// 广播id broadcase id: 必须唯一
        'class'     => \Zwei\Kafka\Consume\BroadCast\OriginBroadCast::class,// 广播类型: 事件来源广播, 即事件来自哪个生产者哪个主题,广播的时候就按来源生产者主题广播
        'enabled'   => true, // 是否广播: true -> 广播, false -> 不广播
        'type'     => [// 广播类型: all -> 所有事件都广播, success -> 成功事件才广播, fail -> 失败事件才广播, exception-> 异常事件广播
            'all', 'success', 'fail', 'exception',
        ],
    ],
    'v0_b_default_common_target' => [// 广播id broadcase id: 必须唯一
        'class'     => \Zwei\Kafka\Consume\BroadCast\TargetBroadCast::class,// 广播类型: 事件广播到目标生产者主题, 即事件广播的某个生产者的某个主题
        'enabled'   => true, // 是否广播: true -> 广播, false -> 不广播
        'producer'  => '', // 生产者名,请看"zwei-kafka-producer.php"配置文件
        'topic'     => '', // 主题名
        'type'      => [// 广播类型: all -> 所有事件都广播, success -> 成功事件才广播, fail -> 失败事件才广播, exception-> 异常事件广播
            'all', 'success', 'fail', 'exception',
        ],
    ],
];