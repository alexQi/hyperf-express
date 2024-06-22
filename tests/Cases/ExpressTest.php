<?php

declare(strict_types=1);
/**
 * 短信测试用例类
 *
 * @link     http://www.swoole.red
 * @contact  1712715552@qq.com
 */

namespace HyperfTest\Cases;

use AlexQiu\Express\Express;
use AlexQiu\Express\ExpressFactory;
use Hyperf\Config\Config;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Utils\ApplicationContext;
use AlexQiu\Express\Exception\NoGatewayAvailableException;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ExpressTest extends TestCase
{
    /**
     * 腾讯短信测试用例
     */
    public function testNotify()
    {
        try {
            $client = $this->getClient();
            $result = $client->notify([], function ($data) {
                var_dump(1111);
            });
            var_dump($result);
            $this->assertEquals($result['aliyun']['status'], 'success');
        } catch (NoGatewayAvailableException $exception) {
            var_dump($exception->getMessage());
        }
        return true;
    }

    protected function getClient()
    {
        $container = Mockery::mock(ContainerInterface::class);

        $config = new Config(
            [
                'timeout'  => 5.0,
                'default'  => [
                    'gateway' => 'kdniao',
                ],
                'gateways' => [
                    'kdniao' => [
                        'app_id'  => '', // SDK APP ID
                        'api_key' => '', // APP KEY
                    ],
                ],
            ]
        );

        $container->shouldReceive('get')->with(ClientFactory::class)->andReturn(new ClientFactory($container));
        $container->shouldReceive('get')->with(Express::class)->andReturn(new Express($config));

        ApplicationContext::setContainer($container);

        $factory = new ExpressFactory();

        return $factory($container);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}

