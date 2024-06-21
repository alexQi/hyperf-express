<?php

declare(strict_types=1);

namespace AlexQiu\Express\Gateway;

use AlexQiu\Express\Exception\GatewayErrorException;
use AlexQiu\Express\Exception\InvalidArgumentException;
use AlexQiu\Express\HasHttpRequest;
use Closure;
use Hyperf\Codec\Json;

/**
 * KdNiaoGateway
 *
 * @author  alex
 * @package AlexQiu\Express\Gateway\KdNiaoGateway
 */
class KdNiaoGateway extends GatewayAbstract
{
    use HasHttpRequest;

    const ENDPOINT_URL = 'https://api.kdniao.com/';

    const HEADERS = [
        "Content-Type" => "application/x-www-form-data-urlencoded",
    ];

    public $data_type;

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
        $request_data = json_encode($request_data);

        return $this->postMessage(
            'Ebusiness/EbusinessOrderHandle.aspx',
            [
                'EBusinessID' => $this->config->get('express.gateways.kdniao.app_id'),
                'RequestType' => "8001",
                'RequestData' => urlencode($request_data),
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
     * @throws GatewayErrorException
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
        $request_data = json_encode($request_data);

        return $this->postMessage(
            'api/dist',
            [
                'EBusinessID' => $this->config->get('express.gateways.kdniao.app_id'),
                'RequestType' => "8008",
                'RequestData' => urlencode($request_data),
                'DataType'    => '2',
                'DataSign'    => $this->generateSign($request_data),
            ]
        );
    }

    public function notify($data)
    {
        // RequestData
        // PushTime
        // Count
        // Data

    }

    /**
     * @param $endpoint
     * @param $params
     * @param $headers
     *
     * @return array
     * @throws GatewayErrorException
     */
    public function postMessage($endpoint, $params)
    {
        $result = $this->get(self::ENDPOINT_URL . $endpoint, $params, self::HEADERS);
        if ($result['Success']) {
            throw new GatewayErrorException($result['Reason'], 500, $result);
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
        return urlencode(
            base64_encode(md5(Json::encode($params) . $this->config->get('express.gateways.kdniao.api_key')))
        );
    }
}
