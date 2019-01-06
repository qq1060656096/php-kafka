<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-14
 * Time: 11:37
 */
return [
    'v0_g_default_bbs_user_limit' => [// 消费者群组名 group id: 必须唯一
        'cluster'   => 'default',// 集群名: zwei-kafka-cluster.php中配置
        'class'     => '',// 使用那种类型消费者消费消息
        'topics'    => [// 主题列表
            "test6",
        ],
        'options'   => [// kafka配置选项
            "offset.store.method" => "broker",// offset保存在broker中
        ],
        'timeout-ms' => 3000,// 消费者超时时间
        'client-ids' => "",
        'broadcast' => [// 事件广播配置
            'enabled'   => true, // 是否广播: true -> 广播, false -> 不广播
            'type'      => 'origin', // 广播类型: origin -> 使用当前消费事件的生产者和主题广播, target -> 广播到指定的生产者的指定主题发送到target生产者主题)
            'target'    => [// 广播到指定生产者主题: 配置了广播类型 type=target才生效
                'producer'  => '', // 生产者名: 请看zwei-kafka-producer.php配置文件
                'topic'     => '', // 广播到那个主题
            ],
            'allow'     => [// 广播方式: all -> 所有事件都广播, success -> 成功事件才广播, fail -> 失败事件才广播, exception-> 异常事件广播
                'all', 'success', 'fail', 'exception',
            ],
        ],
        'events'    => [
            // 事件名 => 事件回调函数(必须是静态方法)
            // 初始化事件 CRM_ZNTK_INIT
            'EVENT_INIT' => '\Zwei\Kafka\AppEventConsumer::testEventCallback',
        ],
    ],
];