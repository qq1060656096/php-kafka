<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-01-03
 * Time: 22:01
 */

namespace Zwei\Kafka\Tests\Consumer\BroadCast;


Trait OriginBroadCastProviderTrait
{
    /**
     * 异常配置数据提供者
     * @return array
     */
    public function configExceptionProvider()
    {

        return [
            [
                'v0_b_default_common_origin',
                [
                    'class'     => '',// 广播类型: 事件广播到目标生产者主题, 即事件广播的某个生产者的某个主题
                    'enabled'   => true, // 是否广播: true -> 广播, false -> 不广播
                    'type'      => [// 广播类型: all -> 所有事件都广播, success -> 成功事件才广播, fail -> 失败事件才广播, exception-> 异常事件广播
                        'all', 'success', 'fail', 'exception',
                    ],
                ],
            ],

            [
                'v0_b_default_common_origin1',
                [
                    'class'     => '',// 广播类型: 事件广播到目标生产者主题, 即事件广播的某个生产者的某个主题
                    'enabled'   => null, // 是否广播: true -> 广播, false -> 不广播
                    'type'      => [// 广播类型: all -> 所有事件都广播, success -> 成功事件才广播, fail -> 失败事件才广播, exception-> 异常事件广播
                        null,
                    ],
                ],
            ],

            [
                'v0_b_default_common_origin2',
                [
                    'class'     => \Zwei\Kafka\Consumer\BroadCast\OriginBroadCast::class,// 广播类型: 事件广播到目标生产者主题, 即事件广播的某个生产者的某个主题
                    'enabled'   => true, // 是否广播: true -> 广播, false -> 不广播
                    'type'      => [// 广播类型: all -> 所有事件都广播, success -> 成功事件才广播, fail -> 失败事件才广播, exception-> 异常事件广播
                        null,
                    ],
                ],
            ],
        ];
    }

    /**
     * 获取正确配置
     * @return array
     */
    public function goodConfig()
    {
        $name = 'goodConfig';
        $config = [
            'class'     => \Zwei\Kafka\Consumer\BroadCast\OriginBroadCast::class,// 广播类型: 事件广播到目标生产者主题, 即事件广播的某个生产者的某个主题
            'enabled'   => false, // 是否广播: true -> 广播, false -> 不广播
            'type'      => [// 广播类型: all -> 所有事件都广播, success -> 成功事件才广播, fail -> 失败事件才广播, exception-> 异常事件广播
                'all', 'success', 'fail', 'exception',
            ],
        ];
        return [$name, $config];
    }
}