<?php
/**
 * Created by PhpStorm.
 * Email: 1060656096@qq.com
 * User: zwei
 * Date: 2018-08-25
 * Time: 21:32
 */

namespace Zwei\Kafka;

use Zwei\Kafka\Exceptions\ConsumerEventException;
use Zwei\Kafka\Helper\ConsumerHelper;


/**
 * 应用事件消费者
 *
 * Class AppEventConsumer
 * @package Zwei\Kafka
 */
class AppEventConsumer
{
    /**
     * 消费者客户端id, 唯一
     * @var string
     */
    protected $clientId = '';

    /**
     * 当前运行分组id
     * @var null|string
     */
    protected $groupId = null;

    /**
     * 当前运行消费者 client_id
     *
     * @var null|array
     */
    protected $clientConfig = null;

    /**
     *
     * 事件列表配置
     * 当前消费者事件配置列表
     * [事件名 => 事件调用静态方法]
     * @var array
     */
    protected $eventListConfig = null;

    /**
     * 消息服务器列表
     * @var array
     */
    protected $brokerList = null;

    /**
     * 主题列表
     * @var array
     */
    protected $topicList = null;

    /**
     * kaka配置选项
     * @var array
     */
    protected $kafkaOptions = null;


    /**
     * 消费kafka消息
     *
     * @param string $clientId 客户端id
     * @param array $config 配置文件
     * @param bool $isDebug 调式默认=false
     * @throws \Exception
     */
    public function handle($clientId, array $config, $isDebug = true)
    {
        $config['kafka_options']['client.id'] = $clientId;
        $this->clientId         = $clientId;
        $this->clientConfig     = $config['client_list'][$this->clientId];
        $this->eventListConfig  = $this->clientConfig['event_list'];
        $this->kafkaOptions     = $config['kafka_options'];
        $this->brokerList       = $config['broker_list'];
        $this->topicList        = $this->clientConfig['topic_list'];
        $this->groupId          = $this->clientConfig['group_id'];
        $timeoutMs              = $this->clientConfig['timeout_ms'];// 单位毫秒

        // 异常格式化信息
        $exceptionFormatStr = <<<str


         [data: %s]:
          client-id: %s
        broker-list: %s
         topic-name: %s
           group-id; %s
         event-name; %s
          event-raw: %s
    exception-class: %s
     exception-code: %s
%s
str;
        $obj        = new ConsumerHelper($this->brokerList, $this->topicList, $this->groupId, $this->kafkaOptions);
        $obj->enabledRebalanceCb();
        $consumer   = $obj->getConsumer();
        while (true) {
            $message = $consumer->consume($timeoutMs);
            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    $rawEventData = $message->payload;
                    // 不是事件格式不处理
                    if (empty($rawEventData)) {
                        break;
                    }

                    $event = json_decode($rawEventData, true);
                    echo $isDebug ? sprintf("\n         [data: %s]:\n          event-raw: %s\n",  date('Y-m-d H:i:s'),$rawEventData) : '';
                    // 不是事件格式不处理
                    if (!is_array($event)) {
                        break;
                    }
                    try {
                        $bool   = $this->executeEvent($event);
                        // 处理成功
                        if ($bool !== false) {
                        } else {// 处理失败
                        }
                    } catch (\Exception $e) {// 异常处理
                        echo sprintf($exceptionFormatStr,
                            date('Y-m-d H:i:s'),
                            $this->clientId,
                            print_r($this->brokerList, true),
                            print_r($this->topicList, true),
                            $this->groupId,
                            $event['eventKey'],
                            json_encode($event),
                            get_class($e),
                            $e->getCode(),
                            $e->getTraceAsString()
                        );
                    }
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:// 没有消息
//                    echo "No more messages; will wait for more\n";
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:// 超时
//                    echo "Timed out\n";
                    break;
                default:
                    throw new \Exception($message->errstr(), $message->err);
                    break;
            }
        }
    }

    /**
     * 执行事件
     *
     * @param array $event
     * @return bool|mixed 失败false, 否则成功
     * @throws ConsumerEventException 事件没有配置抛出异常
     */
    protected function executeEvent(array $event) {
        $eventKey = $event['eventKey'];
        // 没有这个事件配置直接成功
        if (!isset($this->eventListConfig[$eventKey])) {
            throw new ConsumerEventException('Event not found config');
        }
        $staticFunc = $this->eventListConfig[$eventKey];
        return call_user_func_array($staticFunc, [$event]);
    }

    /**
     * 测试消费事件
     *
     * @param array $event
     * @return bool
     * @throws \Exception
     */
    public static function testEventCallback(array $event) {
        $event['date'] = date('Y-m-d H:i:s');
        print_r($event);
        return true;
    }

}