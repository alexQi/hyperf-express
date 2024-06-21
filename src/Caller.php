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
        $results      = [];
        $isSuccessful = false;
        try {
            $results[$gateway] = [
                'gateway' => $gateway,
                'status'  => self::STATUS_SUCCESS,
                'result'  => $this->express->gateway($gateway)->track($data),
            ];
            $isSuccessful      = true;
        } catch (\Exception $e) {
            $results[$gateway] = [
                'gateway'   => $gateway,
                'status'    => self::STATUS_FAILURE,
                'exception' => $e,
            ];
        } catch (\Throwable $e) {
            $results[$gateway] = [
                'gateway'   => $gateway,
                'status'    => self::STATUS_FAILURE,
                'exception' => $e,
            ];
        }

        if (!$isSuccessful) {
            throw new NoGatewayAvailableException($results);
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
        $results      = [];
        $isSuccessful = false;
        try {
            $results[$gateway] = [
                'gateway' => $gateway,
                'status'  => self::STATUS_SUCCESS,
                'result'  => $this->express->gateway($gateway)->subscribe($data),
            ];
            $isSuccessful      = true;
        } catch (\Exception $e) {
            $results[$gateway] = [
                'gateway'   => $gateway,
                'status'    => self::STATUS_FAILURE,
                'exception' => $e,
            ];
        } catch (\Throwable $e) {
            $results[$gateway] = [
                'gateway'   => $gateway,
                'status'    => self::STATUS_FAILURE,
                'exception' => $e,
            ];
        }

        if (!$isSuccessful) {
            throw new NoGatewayAvailableException($results);
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
    public function notify($data, $gateway)
    {
        $results      = [];
        $isSuccessful = false;
        try {
            $results[$gateway] = [
                'gateway' => $gateway,
                'status'  => self::STATUS_SUCCESS,
                'result'  => $this->express->gateway($gateway)->notify($data),
            ];
            $isSuccessful      = true;
        } catch (\Exception $e) {
            $results[$gateway] = [
                'gateway'   => $gateway,
                'status'    => self::STATUS_FAILURE,
                'exception' => $e,
            ];
        } catch (\Throwable $e) {
            $results[$gateway] = [
                'gateway'   => $gateway,
                'status'    => self::STATUS_FAILURE,
                'exception' => $e,
            ];
        }

        if (!$isSuccessful) {
            throw new NoGatewayAvailableException($results);
        }
        return $results;
    }
}