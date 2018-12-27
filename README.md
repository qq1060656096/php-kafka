PHP Event Kafka
=====

介绍
----

Event Kafka是一个基于事件消费的kafka消费者和生产者, 你可以轻松使用示例和配置开发你的应用

开发文档
=====

|  名称     | 多集群   | 跨集群转发事件 | 阿里云kafka | 同步 | 异步 |
| -------  |:--------:| ------------| ----------- | --- | ---- |
| 生产者    | 支持     |              | 支持        | 支持 | 支持 |
| 消费者    | 支持     | 支持          | 支持       | 支持 | 支持  |





----
* **已撰稿** [介绍(Intro)](docs/md/0.0-INTRO.md)
* **已撰写** [安装(Install)](docs/md/1.0-INSTALL.md)
* **未撰写** [kafka消费者](docs/md/2.0-CONSUMER.md)


![Event Kafka流程图](docs/images/event-kafka.png)


### 使用示例

```php
<?php

# 生产者示例
// 用户注册
$eventData = [
    'user'  => 'phpunit.20181227.235950',
    'pass'  => '123456',
    'qq'    => '1060656096',
    'email' => '1060656096@qq.com',
];
\Zwei\Kafka\Event::getProducer('v0_p_default_common_user_register')->sendEvent('USER_REGISTER', $eventData, ['test']);
\Zwei\Kafka\Event::getProducer("生产者名")->sendEvent("事件名", ["事件数据"], ["主题名"]);


```

### 单元测试
```sh
php vendor/phpunit/phpunit/phpunit ./tests/
```

### 常用命令示例

```sh

```

