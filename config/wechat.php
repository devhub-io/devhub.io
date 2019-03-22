<?php

/*
 * This file is part of devhub.io.
 *
 * (c) sysatom <sysatom@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    'debug' => env('WECHAT_DEBUG'),
    'app_id' => env('WECHAT_APP_ID'),
    'secret' => env('WECHAT_SECRET'),
    'token' => env('WECHAT_TOKEN'),

    //'aes_key' => env('WECHAT_AES_KEY'),

    'log' => [
        'level' => 'debug',
        'file' => storage_path() . '/logs/wechat.log',
    ]

];
