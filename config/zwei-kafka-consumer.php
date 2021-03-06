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
        'broadcast' => 'v0_b_default_common_target',
        'events'    => [
            // 事件名 => 事件回调函数(必须是静态方法)
            // 初始化事件 CRM_ZNTK_INIT
            'EVENT_INIT' => '\Zwei\Kafka\AppEventConsumer::testEventCallback',
        ],
    ],
];