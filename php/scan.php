<?php

// define('BASE_DIR', '/home/ganlv/Downloads');
define('BASE_DIR', "C:\Users\Ganlv\Downloads");
$list = scan(BASE_DIR);
file_put_contents(dirname(__DIR__) . '/public/list.json', json_encode($list, JSON_UNESCAPED_UNICODE));

function scan($dir)
{
    return [
        'name' => '/',
        'children' => scanRecursive($dir),
    ];
}

function scanRecursive($dir)
{
    $result = [];
    foreach (scandir($dir) as $name) {
        if ($name[0] === '.') {
            continue;
        }
        $path = $dir . DIRECTORY_SEPARATOR . $name;
        if (is_dir($path)) {
            $result[] = [
                'name' => $name,
                'children' => scanRecursive($path),
            ];
        } else {
            $result[] = [
                'name' => $name,
                'size' => filesize($path),
                'time' => filectime($path),
            ];
        }
    }
    return $result;
}
