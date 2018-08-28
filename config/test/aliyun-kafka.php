<?php
// 阿里云kafka配置
return [
    'broker_list' => [
        "192.168.199.182:9092",
    ],
    "kafka_options" => [// 阿里云kafka连接
        "security.protocol" => "sasl_ssl",
        "sasl.mechanisms" => "PLAIN",
        "api.version.request" => true,
        "sasl.username" => "user",
        "sasl.password" => "pass",
        "ssl.ca.location" => __DIR__.'/test/ca-cert',// 证书路径
        "offset.store.method" => "broker",// offset保存在broker中
    ],
    "kafka_options" => [
        "offset.store.method" => "broker",// offset保存在broker中
    ],
    // 消费者列表
    'client_list' => [
        'consumer_client_id_1' => [// 消费者客户端id
            'topic_list'    => [// 主题列表
                "test6",
            ],
            'group_id'      => "test2018",// 分组id
            'timeout_ms'    => 2000,//(单位毫秒) 消费者等待时间

            'event_list'    => [
                // 事件名 => 事件回调函数(必须是静态方法)
                // 初始化事件 CRM_ZNTK_INIT
                'EVENT_INIT' => '\Zwei\Kafka\AppEventConsumer::testEventCallback',

                // 新增用户事件 CRM_ZNTK_ADD_USER
                'EVENT_ADD_USER' => '\Zwei\Kafka\AppEventConsumer::testEventCallback',

                // 删除用户事件 CRM_ZNTK_DEL_USER
                'EVENT_DEL_USER' => '\Zwei\Kafka\AppEventConsumer::testEventCallback',

                // 修改用户事件 CRM_ZNTK_UPDATE_USER
                'EVENT_UPDATE_USER' => '\Zwei\Kafka\AppEventConsumer::testEventCallback',
            ],
        ],
    ]
];