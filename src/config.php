<?php

/*
 * This file is part of the nilsir/pacypay.
 *
 * (c) nilsir <nilsir@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

return [
    'debug' => env('PACYPAY_DEBUG', false), // 是否开启调试

    'key' => env('PACYPAY_KEY', 'your-key'), // 秘钥
    'merchant_no' => env('PACYPAY_MNO', 'your-merchant_no'), // 商户号
    'version_no' => env('PACYPAY_VERSION_NO', 'your-version_no'), // api版本

    'production' => env('PACYPAY_PRODUCTION', false), // 是否正式环境
    'log' => [
        'level' => env('PACYPAY_LOG_LEVEL', 'debug'),
        'permission' => 0777,
        'file' => env('PACYPAY_LOG_FILE', storage_path('logs/pacypay.log')), // 开启调试时有效, 可指定日志文件地址
    ],
];
