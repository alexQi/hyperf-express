<?php
/**
 * 配置文件
 *
 * @link     http://www.swoole.red
 * @contact  1712715552@qq.com
 */

return [
    // HTTP 请求的超时时间（秒）
    'timeout'  => 5.0,

    // 默认配置
    'default'  => [
        'gateway' => 'kdniao',
    ],
    // 可用的网关配置
    'gateways' => [
        'kdniao' => [
            'app_id'  => '', // SDK APP ID
            'api_key' => '', // APP KEY
        ],
    ],
];
