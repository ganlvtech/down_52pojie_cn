<?php

// ==================== config ====================

// Windows
define('BASE_DIR', 'C:\Users\Ganlv\Downloads');
// define('OUTPUT_FILE', dirname(__DIR__) . '\public\list.json');

// Use jsonp
define('OUTPUT_FILE', dirname(__DIR__) . '\public\list.js');

// Linux
// define('BASE_DIR', '/home/ganlv/Downloads');
// define('OUTPUT_FILE', BASE_DIR . '/list.json');
// define('OUTPUT_FILE', BASE_DIR . '/list.js');

$exclude_files = [
    '/list.json',
    '/list.js',
];


// ==================================================

define('JSONP_CALLBACK', '__jsonpCallbackDown52PojieCn');
date_default_timezone_set('Asia/Shanghai');

foreach ($exclude_files as &$file) {
    $file = BASE_DIR . $file;
}
$list = scan(BASE_DIR, $exclude_files);

$output = json_encode($list, JSON_UNESCAPED_UNICODE);
if (substr(OUTPUT_FILE, -3) === '.js') {
    $output = JSONP_CALLBACK . '(' . $output . ');';
}
file_put_contents(OUTPUT_FILE, $output);

function scan($dir, $exclude_files = [])
{
    return [
        'name' => '/',
        'children' => scanRecursive($dir, $exclude_files),
    ];
}

function scanRecursive($dir, $exclude_files = [])
{
    $result = [];
    foreach (scandir($dir) as $name) {
        if ($name[0] === '.') {
            continue;
        }
        $path = $dir . '/' . $name;
        if (in_array($path, $exclude_files)) {
            continue;
        }
        if (is_dir($path)) {
            $result[] = [
                'name' => $name,
                'children' => scanRecursive($path),
            ];
        } else {
            $result[] = [
                'name' => $name,
                'size' => filesize($path),
                'time' => filemtime($path),
            ];
        }
    }
    return $result;
}
