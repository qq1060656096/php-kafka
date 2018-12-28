<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-14
 * Time: 11:37
 */
return [
    // bbs服务阿里云kafka集群
    'v0_p_aliyun_bbs' => [// 生产者名(必须唯一): 阿里云kafka bbs微服务生产者
        'cluster'   => 'aliyun',// 集群名(zwei-kafka-cluster.php中配置): 阿里云kafka集群
        'class'     => \Zwei\Kafka\Producer\SyncProducer::class,// 使用那种类型生产者发送消息: 同步生产者
        'topics'    => [// 主题列表
            "v0_t_aliyun_bbs",
        ],
        'options'   => [// kafka配置选项
            "security.protocol"     => "sasl_ssl",
            "sasl.mechanisms"       => "PLAIN",
            "api.version.request"   => true,
            "sasl.username"         => "user",
            "sasl.password"         => "pass",
            "ssl.ca.location"       => __DIR__.'/test/ca-cert',// 证书路径
            "offset.store.method"   => "broker",// offset保存在broker中
        ],
    ],

    'v0_p_docker_common_user' => [// 生产者名(必须唯一):
        'cluster'   => 'docker',// 集群名(zwei-kafka-cluster.php中配置): docker kafka集群
        'class'     => \Zwei\Kafka\Producer\AsyncProducer::class,// 使用那种类型生产者发送消息: 异步生产者
        'topics'    => [// 主题列表
            "v0_t_docker_test",
        ],
        'options'   => [// kafka配置选项
            "offset.store.method" => "broker",// offset保存在broker中
        ],
    ],

    'v0_p_docker2_common_order' => [// 生产者名(必须唯一): docker kafka2正常集群
        'cluster'   => 'docker2',// 集群名(zwei-kafka-cluster.php中配置): docker kafka集群
        'class'     => \Zwei\Kafka\Producer\SyncProducer::class,// 使用那种类型生产者发送消息: 同步生产者
        'topics'    => [// 主题列表
            "v0_t_docker2_test",
            "v0_t_docker2_test2",
        ],
        'options'   => [// kafka配置选项
            "offset.store.method" => "broker",// offset保存在broker中
        ],
    ],



    'v0_p_exception_broker' => [// 生产者名(必须唯一): kafka异常broker,异步生产者单元测试
        'cluster'   => 'exception',// 集群名(zwei-kafka-cluster.php中配置): 异常kafka集群
        'class'     => \Zwei\Kafka\Producer\SyncProducer::class,// 使用那种类型生产者发送消息: 同步生产者
        'topics'    => [// 主题列表
            "v0_t_exception_broker_topic",
        ],
        'options'   => [// kafka配置选项
            "offset.store.method" => "broker",// offset保存在broker中
        ],
    ],

    'v0_p_docker_no_exist_topic' => [// 生产者名(必须唯一): kafka不存在的topic,异步生产者单元测试
        'cluster'   => 'docker',// 集群名(zwei-kafka-cluster.php中配置): docker kafka集群
        'class'     => \Zwei\Kafka\Producer\SyncProducer::class,// 使用那种类型生产者发送消息: 同步生产者
        'topics'    => [// 主题列表
            "v0_t_docker_no_exist_topic",
            "v0_t_docker_no_exist_topic2",
        ],
        'options'   => [// kafka配置选项
            "offset.store.method" => "broker",// offset保存在broker中
        ],
    ],

    'v0_p_docker_sync_common_user' => [// 生产者名(必须唯一): 同步生产者
        'cluster'   => 'docker',// 集群名(zwei-kafka-cluster.php中配置): docker kafka集群
        'class'     => \Zwei\Kafka\Producer\SyncProducer::class,// 使用那种类型生产者发送消息: 异步生产者
        'topics'    => [// 主题列表
            "v0_t_docker_test",
        ],
        'options'   => [// kafka配置选项
            "offset.store.method" => "broker",// offset保存在broker中
        ],
    ],

    'v0_p_docker_async_common_user' => [// 生产者名(必须唯一): 异步生产者
        'cluster'   => 'docker',// 集群名(zwei-kafka-cluster.php中配置): docker kafka集群
        'class'     => \Zwei\Kafka\Producer\AsyncProducer::class,// 使用那种类型生产者发送消息: 异步生产者
        'topics'    => [// 主题列表
            "v0_t_docker_test",
        ],
        'options'   => [// kafka配置选项
            "offset.store.method" => "broker",// offset保存在broker中
        ],
    ],

];