### 一款查询物流信息的hyperf组件</p>

## 特点

1. 可兼容目前市面多家服务商
1. 一套写法兼容所有平台
1. 简单配置即可灵活增减服务商
1. 统一的返回值格式，便于日志与监控
1. 更多平台可自行接入...

## 平台支持

- [快递鸟](https://www.kdniao.com/)

## 环境需求

- PHP >= 7.0

## 安装

```
$ composer require "alex-qiu/hyperf-express"
```

```
$ php bin/hyperf.php vendor:publish alex-qiu/hyperf-express
```

## 使用

```php
use AlexQiu\Express\Express;

$config = [
    // HTTP 请求的超时时间（秒）
    'timeout' => 5.0,

    // 默认配置
    'default' => [
        'gateway' => 'kdniao',
    ],
    // 可用的网关配置
    'gateways' => [
        'kdniao' => [
            'app_id'  => '', // SDK APP ID
            'api_key' => '', // APP KEY
        ],
    ],
];

/**
 * @var Express
 */
public $express;

public function __construct(ExpressInterface $express)
{
    $this->express = $express;
}

public function index()
{
    return $this->express->track([
        "logistic_code" => "JDVA00003618100",
        "shipper_code"  => "JD"
    ]);
}
```