F<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2018-12-29
 * Time: 21:42
 */

namespace Zwei\Kafka\Consumer;


class EventForward
{
    const CONFIG_KEY_PRODUCER = 'producer';
    const CONFIG_KEY_TOPIC = 'topic';

    protected $forwardConfig = null;

    /**
     * 是否转发
     *
     * @param array $forward
     * @param array $event
     * @return bool
     */
    public function handle(array $forward, array $event)
    {
        if (!$forward) {
            return false;
        }
        return true;
    }
}