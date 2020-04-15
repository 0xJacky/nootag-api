<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: aliyun.php
 * Description:
 * Date: 2020/01/03
 * Time: 9:20 下午
 */

return [
    'access_key_id' => env('ALIYUN_ACCESS_KEY_ID'),
    'access_key_secret' => env('ALIYUN_ACCESS_KEY_SECRET'),
    'endpoint' => env('ALIYUN_ENDPOINT'),
    'oss_bucket' => env('ALIYUN_OSS_BUCKET'),
    'oss_domain' => env('ALIYUN_OSS_DOMAIN'),
    'sms_sign_name' => env('ALIYUN_SMS_SIGN_NAME'),
    'sms_template_code' => env('ALIYUN_SMS_TEMPLATE_CODE')
];
