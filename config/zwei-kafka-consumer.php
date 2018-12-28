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
        'group-id'  => "consumer_group_id",// 分组id
        'client-ids' => "",
        'forward'   => [
            'producer'  => '',// 转发到那个生产者,
            'topic'     => '',// 转发到生产者所在集群的那个topic
        ],// 是否转发某个生产者
        'broadcast' => [// 事件是否广播
            'producer'  => '',// 广播到那个生产者,
            'topic'     => '',// 广播到那个topic
            'success',
            'fail',
            'exception',//
        ],// all -> 所有事件都广播, success -> 成功事件才广播, fail -> 失败事件才广播, exception-> 异常事件广播
        'events'    => [
            // 事件名 => 事件回调函数(必须是静态方法)
            // 初始化事件 CRM_ZNTK_INIT
            'EVENT_INIT' => '\Zwei\Kafka\AppEventConsumer::testEventCallback',
        ],
    ],
];