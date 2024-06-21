<?php

declare(strict_types=1);
/**
 * 支持hyperf 容器映射工程类
 *
 * @link     http://www.swoole.red
 * @contact  1712715552@qq.com
 */

namespace AlexQiu\Express;

use AlexQiu\Express\Contract\ExpressInterface;
use AlexQiu\Express\Exception\InvalidArgumentException;
use Psr\Container\ContainerInterface;

class ExpressFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $factory = $container->get(Express::class);
        if (!($factory instanceof ExpressInterface)) {
            throw new InvalidArgumentException('on implements ExpressInterface');
        }
        return $factory;
    }
}