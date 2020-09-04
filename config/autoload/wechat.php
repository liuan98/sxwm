<?php
declare(strict_types=1);

return [
    /*
     * 微信支付
     */
    'payment' => [
        'default' => [
            'sandbox'            => env('WECHAT_PAYMENT_SANDBOX', false),
            'app_id'             => env('WECHAT_PAYMENT_APPID', 'wxbcc63819dabd05f7'),
            'mch_id'             => env('WECHAT_PAYMENT_MCH_ID', '1565574201'),
            'key'                => env('WECHAT_PAYMENT_KEY', 'snbl2019snbl2019snbl2019snbl2019'),
            'cert_path'          => env('WECHAT_PAYMENT_CERT_PATH', 'path/to/cert/apiclient_cert.pem'),    // XXX: 绝对路径！！！！
            'key_path'           => env('WECHAT_PAYMENT_KEY_PATH', 'path/to/cert/apiclient_key.pem'),      // XXX: 绝对路径！！！！
            'notify_url'         =>  env('WECHAT_PAYMENT_NOTIFY_URL', ''),                             // 默认支付结果通知地址
        ],

    ],
    'mini_program' => [
        'default' => [
            'app_id'  => env('WECHAT_MINI_PROGRAM_APPID', 'wxbcc63819dabd05f7'),
            'secret'  => env('WECHAT_MINI_PROGRAM_SECRET', 'db606fa28d139adaaa4ad2b6d9b83b1d'),
            'token'   => env('WECHAT_MINI_PROGRAM_TOKEN', ''),
            'aes_key' => env('WECHAT_MINI_PROGRAM_AES_KEY', ''),
        ],
    ],

];