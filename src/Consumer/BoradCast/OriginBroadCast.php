<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-01-02
 * Time: 19:15
 */

namespace Zwei\Kafka\Consume\BroadCast;


use Zwei\Kafka\Consumer\BroadCastAbstract;
use Zwei\Kafka\Event\EventHelper;
use Zwei\Kafka\Exceptions\Config\BroadCastConfigException;

class OriginBroadCast extends BroadCastAbstract
{
    /**
     * @inheritdoc
     */
    public function handle(array $event, $type)
    {
        list($producerName, $topicName) = EventHelper::getEventLastOriginProducer($event);
        return $this->broadCastEvent($event, $type, $producerName, $topicName);
    }
}