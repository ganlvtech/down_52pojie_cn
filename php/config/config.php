<?php

return [
    // 扫描的根目录（绝对路径）
    'BASE_PATH' => "C:\Users\Ganlv\Downloads",
    // 'BASE_PATH' => '/home/ganlv/Downloads',
    // 排除的文件（相对于 BASE_PATH）
    'EXCLUDE_FILES' => [
        '/list.json',
        '/list.js',
    ],
    // 描述文件（绝对路径）
    'DESCRIPTION_FILE' => dirname(__DIR__) . '/data/description.yml',
    // 输出的文件类型（json 或者 jsonp）
    // 'OUTPUT_TYPE' => 'jsonp',
    'OUTPUT_TYPE' => 'json',
    // 输出的文件路径（绝对路径）
    'OUTPUT_FILE' => 'D:\www\PhpstormProjects\down_52pojie_cn\dist\list.json',
    // 'OUTPUT_FILE' => 'C:\Users\Ganlv\Downloads\.list.js',
    // jsonp 回调函数名（需要与 index.html 中的设置相同）
    'JSONP_CALLBACK' => '__jsonpCallbackDown52PojieCn',
];
