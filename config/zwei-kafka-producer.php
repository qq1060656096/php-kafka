<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-14
 * Time: 11:37
 */
return [
    'v0_p_default_common_user_register' => [// 生产者名(必须唯一): 用户注册
        'cluster'   => 'default',// 集群名: zwei-kafka-cluster.php中配置
        'class'     => \Zwei\Kafka\Producer\AsyncProducer::class,// 使用那种类型生产者发送消息: 异步生产者
        'topics'    => [// 主题列表
            "test6",
        ],
        'options'   => [// kafka配置选项
            "offset.store.method" => "broker",// offset保存在broker中
        ],
    ],

    'v0_p_default_phpunit_async_normal' => [// 生产者名(必须唯一): 正常异步生产者单元测试
        'cluster'   => 'normal',// 集群名: zwei-kafka-cluster.php中配置
        'class'     => \Zwei\Kafka\Producer\SyncProducer::class,// 使用那种类型生产者发送消息: 同步生产者
        'topics'    => [// 主题列表
            "v0_t_normal_phpunit",
            "test_normal",
        ],
        'options'   => [// kafka配置选项
            "offset.store.method" => "broker",// offset保存在broker中
        ],
    ],

    'v0_p_default_phpunit_async_exception' => [// 生产者名(必须唯一): kafka异常broker,异步生产者单元测试
        'cluster'   => 'exception',// 集群名: zwei-kafka-cluster.php中配置
        'class'     => \Zwei\Kafka\Producer\SyncProducer::class,// 使用那种类型生产者发送消息: 同步生产者
        'topics'    => [// 主题列表
            "v0_t_normal_phpunit",
            "test_normal",
        ],
        'options'   => [// kafka配置选项
            "offset.store.method" => "broker",// offset保存在broker中
        ],
    ],

    'v0_p_default_phpunit_async_no_exist_topic' => [// 生产者名(必须唯一): kafka不存在的topic,异步生产者单元测试
        'cluster'   => 'normal',// 集群名: zwei-kafka-cluster.php中配置
        'class'     => \Zwei\Kafka\Producer\SyncProducer::class,// 使用那种类型生产者发送消息: 同步生产者
        'topics'    => [// 主题列表
            "v0_t_no_exist_topic_phpunit",
            "v0_t_no_exist_topic_phpunit2",
        ],
        'options'   => [// kafka配置选项
            "offset.store.method" => "broker",// offset保存在broker中
        ],
    ],

];