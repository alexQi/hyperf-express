<?php

declare(strict_types=1);

namespace AlexQiu\Express\Gateway;

use AlexQiu\Express\Exception\InvalidArgumentException;
use AlexQiu\Express\HasHttpRequest;
use Hyperf\Codec\Json;

/**
 * KdNiaoGateway
 *
 * @author  alex
 * @package AlexQiu\Express\Gateway\KdNiaoGateway
 */
class KdniaoGateway extends GatewayAbstract
{
    use HasHttpRequest;

    const ENDPOINT_URL = 'https://api.kdniao.com/';

    const HEADERS = [
        "Content-Type" => "application/x-www-form-data-urlencoded",
    ];

    public $data_type;

    /**
     * @param $data
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public function track($data)
    {
        if (!isset($data["logistic_code"])) {
            throw new InvalidArgumentException('logistic_code is required');
        }
        if (!isset($data["shipper_code"])) {
            throw new InvalidArgumentException('shipper_code is required');
        }
        $request_data = [
            'LogisticCode' => $data["logistic_code"],
            'ShipperCode'  => $data["shipper_code"],
        ];
        if (isset($data["customer_name"])) {
            $request_data["CustomerName"] = $data["customer_name"];
        }
        return $this->postMessage(
            'Ebusiness/EbusinessOrderHandle.aspx',
            [
                'EBusinessID' => $this->config->get('express.gateways.kdniao.app_id'),
                'RequestType' => "8001",
                'RequestData' => urlencode(json_encode($request_data)),
                'DataType'    => "2",
                'DataSign'    => $this->generateSign($request_data),
            ]
        );
    }

    /**
     * @param $data
     * @param $extend
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public function subscribe($data, $extend = [])
    {
        if (!isset($data["logistic_code"])) {
            throw new InvalidArgumentException('logistic_code is required');
        }
        if (!isset($data["shipper_code"])) {
            throw new InvalidArgumentException('shipper_code is required');
        }
        $request_data = [
            'LogisticCode' => $data["logistic_code"],
            'ShipperCode'  => $data["shipper_code"],
        ];
        if (isset($data["customer_name"])) {
            $request_data["CustomerName"] = $data["customer_name"];
        }
        return $this->postMessage(
            'api/dist',
            [
                'EBusinessID' => $this->config->get('express.gateways.kdniao.app_id'),
                'RequestType' => "8008",
                'RequestData' => urlencode(json_encode($request_data)),
                'DataType'    => '2',
                'DataSign'    => $this->generateSign($request_data),
            ]
        );
    }

    /**
     * @param $data
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function notify($data)
    {
        if (!isset($data['Success'])) {
            throw new InvalidArgumentException('notify params is invalid', 500);
        }
        return $data["Data"];
    }

    /**
     * @param $endpoint
     * @param $params
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public function postMessage($endpoint, $params)
    {
        $result = $this->get(self::ENDPOINT_URL . $endpoint, $params, self::HEADERS);
        if (!isset($result['Success'])) {
            throw new InvalidArgumentException($result['Reason'], 500, $result);
        }
        return $result;
    }

    /**
     * @param $params
     *
     * @return string
     */
    protected function generateSign($params)
    {
        $body = str_replace("\\/", "/", Json::encode($params));
        return urlencode(
            base64_encode(md5($body . $this->config->get('express.gateways.kdniao.api_key')))
        );
    }
}
