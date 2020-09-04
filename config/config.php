<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

use Hyperf\Contract\StdoutLoggerInterface;
use Psr\Log\LogLevel;

return [
    'app_name' => env('APP_NAME', 'xiaoxi'),
    StdoutLoggerInterface::class => [
        'log_level' => [
            LogLevel::ALERT,
            LogLevel::CRITICAL,
            LogLevel::DEBUG,
            LogLevel::EMERGENCY,
            LogLevel::ERROR,
            LogLevel::INFO,
            LogLevel::NOTICE,
            LogLevel::WARNING,
        ],
    ],
    'ddLog' => [
        'ONE_REALM' => 'http://xiaox.gzyxxy.com/',//域名
//        'REALM' => 'http://book.gzyxxy.com/',//域名
//        'OAPI_HOST' => 'https://oapi.dingtalk.com',//网关
//        'AGENT_ID' => '624099798',//应用id
//        'APP_KEY' => 'dingidhrwls1ehxztywp',//key
//        'APP_SECRET' => 'rFXW_BeMD2HI4sLjSSgMNs1gIi4vZFEfaUjjWtZGFNT9pb8yxmAIpbnleyx0Fkrx',//密钥
    ]
];
