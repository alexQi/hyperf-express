<?php

declare(strict_types=1);
/**
 *
 * @link     http://www.swoole.red
 * @contact  1712715552@qq.com
 */

namespace AlexQiu\Express;

use AlexQiu\Express\Contract\ExpressInterface;
use AlexQiu\Express\Contract\GatewayInterface;
use AlexQiu\Express\Exception\InvalidArgumentException;
use AlexQiu\Express\Exception\NoGatewayAvailableException;
use Closure;
use Hyperf\Contract\ConfigInterface;
use function call_user_func;
use function class_exists;
use function in_array;
use function str_replace;
use function ucfirst;

class Express implements ExpressInterface
{
    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @var array
     */
    protected $customCreators = [];
    /**
     * @var array
     */
    protected $gateways = [];
    /**
     * @var Caller
     */
    protected $caller;

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param            $data
     * @param array|null $gateway
     *
     * @return array|mixed
     * @throws NoGatewayAvailableException
     */
    public function track($data, $gateway = null)
    {
        return $this->getCaller()->track($data, $this->formatGateways($gateway));
    }

    /**
     * @param $data
     * @param $gateway
     *
     * @return array|mixed
     * @throws NoGatewayAvailableException
     */
    public function subscribe($data, $gateway = null)
    {
        return $this->getCaller()->subscribe($data, $this->formatGateways($gateway));
    }

    /**
     * @param         $data
     * @param Closure $closure
     * @param         $gateway
     *
     * @return array|mixed
     * @throws NoGatewayAvailableException
     */
    public function notify($data, Closure $closure, $gateway = null)
    {
        return $this->getCaller()->notify($data, $closure, $this->formatGateways($gateway));
    }

    /**
     * Create a gateway.
     *
     * @param string $name
     *
     * @return GatewayInterface
     *
     * @throws InvalidArgumentException
     */
    public function gateway($name)
    {
        if (!isset($this->gateways[$name])) {
            $this->gateways[$name] = $this->createGateway($name);
        }
        return $this->gateways[$name];
    }

    /**
     * Register a custom driver creator Closure.
     *
     * @param string   $name
     * @param \Closure $callback
     *
     * @return $this
     */
    public function extend($name, Closure $callback)
    {
        $this->customCreators[$name] = $callback;
        return $this;
    }

    /**
     * @return Caller
     */
    public function getCaller()
    {
        return $this->caller ? : $this->caller = new Caller($this);
    }

    /**
     * Create a new driver instance.
     *
     * @param string $name
     *
     * @return GatewayInterface
     *
     * @throws InvalidArgumentException
     */
    protected function createGateway($name)
    {
        if (isset($this->customCreators[$name])) {
            $gateway = $this->callCustomCreator($name);
        } else {
            $className = $this->formatGatewayClassName($name);
            $gateway   = $this->makeGateway($className, $this->config);
        }
        if (!($gateway instanceof GatewayInterface)) {
            throw new InvalidArgumentException(
                \sprintf('Gateway "%s" must implement interface %s.', $name, GatewayInterface::class)
            );
        }
        return $gateway;
    }

    /**
     * Make gateway instance.
     *
     * @param string          $gateway
     * @param ConfigInterface $config
     *
     * @return GatewayInterface
     *
     * @throws InvalidArgumentException
     */
    protected function makeGateway($gateway, $config)
    {
        if (!class_exists($gateway) || !in_array(GatewayInterface::class, \class_implements($gateway))) {
            throw new InvalidArgumentException(\sprintf('Class "%s" is a invalid express gateway.', $gateway));
        }
        return new $gateway($config);
    }

    /**
     * Format gateway name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function formatGatewayClassName($name)
    {
        if (class_exists($name) && in_array(GatewayInterface::class, \class_implements($name))) {
            return $name;
        }
        $name = ucfirst(str_replace(['-', '_', ''], '', $name));
        return __NAMESPACE__ . "\\Gateway\\{$name}Gateway";
    }

    /**
     * Call a custom gateway creator.
     *
     * @param string $gateway
     *
     * @return mixed
     */
    protected function callCustomCreator($gateway)
    {
        return call_user_func(
            $this->customCreators[$gateway],
            $this->config->get("express.gateways.$gateway", [])
        );
    }

    /**
     * @param $gateway
     *
     * @return mixed
     * @throws NoGatewayAvailableException
     */
    protected function formatGateways($gateway)
    {
        if (!$gateway) {
            $gateway = $this->config->get('express.default.gateway');
        }
        if (!$gateway) {
            throw new NoGatewayAvailableException("unkonw express gateway");
        }
        return $gateway;
    }
}