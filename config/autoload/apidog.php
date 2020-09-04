<?php

return [
    // enable false 将不会生成 swagger 文件
    'enable' => env('APP_ENV') !== 'production',
    // swagger 配置的输出文件
    'output_file' => BASE_PATH . '/public/swagger.json',
    // 忽略的hook, 非必须 用于忽略符合条件的接口, 将不会输出到上定义的文件中
    'ignore' => function ($controller, $action) {
        return false;
    },
    // 自定义验证器错误码、错误描述字段
    'error_code' => 400,
    'http_status_code' => 400,
    'field_error_code' => 'err_code',
    'field_error_msg' => 'err_msg',
    // swagger 的基础配置
    'swagger' => [
        'swagger' => '2.0',
        'info' => [
            'description' => 'hyperf文档测试',
            'version' => '1.0.0',
            'title' => 'hyperf文档测试',
        ],
        'host' => 'cwt.hyperfdemo.cn',
        'schemes' => ['http'],
    ],
];
