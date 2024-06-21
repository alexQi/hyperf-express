<?php

declare(strict_types=1);
/**
 *
 * @link     http://www.swoole.red
 * @contact  1712715552@qq.com
 */

namespace AlexQiu\Express\Contract;

interface GatewayInterface
{
    /**
     * 获取服务商名称
     *
     * @return string
     */
    public function getName();

    /**
     * 物流查询
     *
     * @param       $data
     * @param array $gateways
     *
     * @return mixed
     */
    public function track($data);

    /**
     * @param $data
     * @param $gateway
     *
     * @return mixed
     */
    public function subscribe($data);

    /**
     * @param $data
     * @param $gateway
     *
     * @return mixed
     */
    public function notify($data);

}