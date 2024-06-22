<?php

declare(strict_types=1);
/**
 * 手机号码实现类
 *
 * @link     http://www.swoole.red
 * @contact  1712715552@qq.com
 */

namespace AlexQiu\Express;

use AlexQiu\Express\Exception\NoGatewayAvailableException;
use Closure;
use Exception;
use Throwable;

class Caller
{
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILURE = 'failure';
    /**
     * @var Express
     */
    protected $express;

    /**
     * Messenger constructor.
     *
     * @param express $express
     */
    public function __construct(Express $express)
    {
        $this->express = $express;
    }

    /**
     * @param $data
     * @param $gateway
     *
     * @return array
     * @throws NoGatewayAvailableException
     */
    public function track($data, $gateway)
    {
        try {
            $results = $this->express->gateway($gateway)->track($data);
        } catch (Exception|Throwable $e) {
            throw new NoGatewayAvailableException($e->getMessage());
        }
        return $results;
    }

    /**
     * @param $data
     * @param $gateway
     *
     * @return array
     * @throws NoGatewayAvailableException
     */
    public function subscribe($data, $gateway)
    {
        try {
            $results = $this->express->gateway($gateway)->subscribe($data);
        } catch (Exception|Throwable $e) {
            throw new NoGatewayAvailableException($e->getMessage());
        }
        return $results;
    }

    /**
     * @param $data
     * @param $gateway
     *
     * @return array
     * @throws NoGatewayAvailableException
     */
    public function notify($data, Closure $closure, $gateway)
    {
        try {
            $results = $this->express->gateway($gateway)->notify($data);
        } catch (Exception|Throwable $e) {
            throw new NoGatewayAvailableException($e->getMessage());
        }
        return call_user_func($closure, $results);
    }
}