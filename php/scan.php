<?php

// ==================== config ====================

define('BASE_DIR', 'C:\Users\Ganlv\Downloads');
define('OUTPUT_FILE', dirname(__DIR__) . '\public\list.json');
// define('BASE_DIR', '/home/ganlv/Downloads');
// define('OUTPUT_FILE', BASE_DIR . '/list.json');
$exclude_files = [
    '/.fancyindex',
    '/list.json',
];



// ==================================================

date_default_timezone_set('Asia/Shanghai');

foreach ($exclude_files as &$file) {
    $file = BASE_DIR . $file;
}
$list = scan(BASE_DIR, $exclude_files);
file_put_contents(OUTPUT_FILE, json_encode($list, JSON_UNESCAPED_UNICODE));

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
