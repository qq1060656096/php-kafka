PHP Kafka
=====

介绍
----

简单的kafka示例, 阿里云kafka示例, 你可以轻松使用示例和配置开发你的应用

开发文档
=====

|  类型     | 是否支持多集群   | 阿里云kafka |
| -------  |:-------------: | --------    |
| 消费者    | 不支持          | 支持        |
| 多消费者  | 不支持          | 支持        |
| 生产者    | 不支持          | 支持        |




----
* **已撰稿** [介绍(Intro)](docs/md/0.0-INTRO.md)
* **已撰写** [安装(Install)](docs/md/1.0-INSTALL.md)
* **未撰写** [kafka消费者](docs/md/2.0-CONSUMER.md)


![Event Kafka流程图](docs/images/event-kafka.png)

### 单元测试
```sh
phpunit --bootstrap ./tests/TestInit.php ./tests/

phpunit --bootstrap ./tests/TestInit.php ./tests/Heplers/CommonHelperTest.php
phpunit --bootstrap ./tests/TestInit.php ./tests/Events
```

### 常用命令示例

```sh
# 消费者示例
php AliyunAppEventConsumer 消费者客户端id
php examples\AliyunAppEventConsumer.php "consumer_client_id_1"

# 生产者示例
php AliyunSendAppEventProducer.php 发送次数 间隔时间  主题名 事件json字符串 分区[默认自动分区]
# 发送10次,每次间隔1秒,发送到"test_topic"主题, 事件内容是 "{}", 发送到默认自动分区
php AliyunSendAppEventProducer.php "10" "1"  "test_topic" "{}"

# 发送10次,每次间隔1秒,发送到"test_topic"主题, 事件内容是 "{}", 发送到1分区
php AliyunSendAppEventProducer.php "10" "1"  "test_topic" "{}" "1"

```

### 多消费者

**分配分区**

```sh
# 消费者1
php examples\AliyunAppEventConsumer.php "consumer_client_id_1"
```

```sh
# 消费者2
php examples\AliyunAppEventConsumer.php "consumer_client_id_1"
```

![分配分区](docs/images/assign-partition.png)

**消费消息**

```sh
# 生产者1, 发送10次,间隔2秒,发送到test6主题,发送到1分区
php examples\AliyunSendAppEventProducer.php "10" "2"  "test6" "{\"id\":1,\"eventKey\":\"EVENT_INIT\",\"name\":\"1111111111\"}" "1"
```

```sh
# 生产者2, 发送10次,间隔2秒,发送到test6主题,发送到3分区
php examples\AliyunSendAppEventProducer.php "10" "2"  "test6" "{\"id\":3,\"eventKey\":\"EVENT_INIT\",\"name\":\"8888\"}" "3"
```

![消费消息](docs/images/multi-consumer.png)