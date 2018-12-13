<?php
// 文件 /config/kafka.php
return [
    'broker_list' => [
        "kafka-cn-internet.aliyun.com:8080",
    ],
    "kafka_options" => [// kafka选项
        "offset.store.method" => "broker",// offset保存在broker中
    ],
    // 消费者列表
    'client_list' => [
        'consumer_client_id' => [// 消费者group id
            'topic_list'    => [// 主题列表
                "CID_alikafka-dhbpc",
            ],
            'group_id'      => "alikafka-crm-clients-sync",// 分组id
            'timeout_ms'    => 3000,//(单位毫秒) 消费者等待时间

            'event_list'    => [
                // 事件名 => 事件回调函数(必须是静态方法)
                // 初始化事件 CRM_ZNTK_INIT
                'CRM_ZNTK_INIT' => '\App\Console\Commands\RdKafkaConsumer::testEventCallback',

                // 新增用户事件 CRM_ZNTK_ADD_USER
                'CRM_ZNTK_ADD_USER' => '\App\Console\Commands\RdKafkaConsumer::testEventCallback',

                // 删除用户事件 CRM_ZNTK_DEL_USER
                'CRM_ZNTK_DEL_USER' => '\App\Console\Commands\RdKafkaConsumer::testEventCallback',

                // 修改用户事件 CRM_ZNTK_UPDATE_USER
                'CRM_ZNTK_UPDATE_USER' => '\App\Console\Commands\RdKafkaConsumer::testEventCallback',
            ],
        ],
    ]
];