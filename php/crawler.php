<?php

date_default_timezone_set('Asia/Shanghai');
define('BASE_URL', 'https://down.52pojie.cn');

$origin = crawlOriginalList('/');
file_put_contents(__DIR__ . '/origin.json', json_encode($origin));

[$result, $size, $time] = format($origin);
$list = [
    'url' => '/',
    'name' => '/',
    'size' => $size,
    'time' => $time,
    'children' => $result,
];

file_put_contents(dirname(__DIR__) . '/list.json', json_encode($list));

function crawlOriginalList($url)
{
    $html = file_get_contents(BASE_URL . $url . '?t=' . time());
    $list = [];
    if (1 === preg_match('#<tbody>(.*?)</tbody>#su', $html, $matches)) {
        $tbody = $matches[1];
        if (preg_match_all('#<tr><td.*?><a href="(.*?)".*?>(.*?)</a></td><td.*?>(.*?)</td><td.*?>(.*?)</td></tr>#su', $tbody, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $href = html_entity_decode($match[1]);
                if ($href !== '../') {
                    $url1 = $url . $href;
                    $name = html_entity_decode($match[2]);
                    $size = html_entity_decode($match[3]);
                    $time = html_entity_decode($match[4]);
                    $item = [
                        'href' => $href,
                        'url' => $url1,
                        'name' => $name,
                        'size' => $size,
                        'time' => $time,
                    ];
                    echo $name, "\t", $url1, PHP_EOL;
                    if ($size === '-') {
                        $item['children'] = crawlOriginalList($url1);
                    }
                    $list[] = $item;
                }
            }
        }
    }
    return $list;
}

function format($origin)
{
    $result = [];
    $size = 0;
    $time = null;
    foreach ($origin as $file) {
        if ($file['size'] === '-') {
            [$children, $size_1, $time_1] = format($file['children']);
            $result[] = [
                'url' => $file['url'],
                'href' => $file['href'],
                'name' => rtrim($file['name'], '/'),
                'size' => $size_1,
                'time' => $time_1,
                'children' => $children,
            ];
        } else {
            $size_1 = getFileBytes($file['size']);
            $time_1 = date(DATE_ATOM, strtotime($file['time']));
            $result[] = [
                'url' => $file['url'],
                'href' => $file['href'],
                'name' => rtrim($file['name'], '/'),
                'size' => $size_1,
                'time' => $time_1,
            ];
        }
        $size += $size_1;
        if ($time_1 > $time) {
            $time = $time_1;
        }
    }
    return [$result, $size, $time];
}

function getFileBytes($size_string)
{
    $last_byte = strtoupper(substr($size_string, -1, 1));
    if ($last_byte === 'G') {
        return (int)$size_string * 1024 * 1024 * 1024;
    } elseif ($last_byte === 'M') {
        return (int)$size_string * 1024 * 1024;
    } elseif ($last_byte === 'K') {
        return (int)$size_string * 1024;
    } else {
        return (int)$size_string;
    }
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    return (substr($haystack, -$length) === $needle);
}