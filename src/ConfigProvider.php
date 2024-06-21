<?php

declare(strict_types=1);
/**
 * hyperf 组件加载配置
 *
 * @link     http://www.swoole.red
 * @contact  1712715552@qq.com
 */

namespace AlexQiu\Express;

use AlexQiu\Express\Contract\ExpressInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                ExpressInterface::class => ExpressFactory::class
            ],
            'annotations'  => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish'      => [
                [
                    'id'          => 'config',
                    'description' => 'The config for express component.',
                    'source'      => __DIR__ . '/../publish/express.php',
                    'destination' => BASE_PATH . '/config/autoload/express.php',
                ],
            ],
        ];
    }
}
