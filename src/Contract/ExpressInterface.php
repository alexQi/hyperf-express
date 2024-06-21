<?php

declare(strict_types=1);

namespace AlexQiu\Express\Contract;

use Closure;

/**
 *
 */
interface ExpressInterface
{
    /**
     * @param       $data
     * @param array $gateways
     *
     * @return mixed
     */
    public function track($data, $gateway = null);

    /**
     * @return mixed
     */
    public function subscribe($data, $gateway = null);

    /**
     * @param $data
     * @param $gateway
     *
     * @return mixed
     */
    public function notify($data, Closure $closure, $gateway = null);

    /**
     * 注册自定义驱动程序创建者闭包。
     *
     * @param string   $name
     * @param \Closure $callback
     *
     * @return $this
     */
    public function extend($name, Closure $callback);

}