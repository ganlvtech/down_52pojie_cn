<?php

date_default_timezone_set('Asia/Shanghai');
define('BASE_URL', 'https://down.52pojie.cn');

$list = getList('/');

$all = 0;
$result = [];
foreach ($list as $item) {
    if ($item['size'] != '-') {
        $all += getFileBytes(trim($item['size']));
        $result[] = [
            'name' => $item['name'],
            'href' => $item['href'],
            'url' => $item['url'],
            'size' => getFileBytes(trim($item['size'])),
            'time' => date(DateTime::ATOM, strtotime($item['time'])),
        ];
    }
}

file_put_contents('php/list.json', json_encode($result));

function getList($url)
{
    $html = file_get_contents(BASE_URL . $url);
    $list = [];
    if (1 === preg_match('#<tbody>(.*?)</tbody>#su', $html, $matches)) {
        $tbody = $matches[1];
        if (preg_match_all('#<tr><td><a href="(.*?)".*?>(.*?)</a></td><td>(.*?)</td><td>(.*?)</td></tr>#su', $tbody, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $href = html_entity_decode($match[1]);
                if ($href !== '../') {
                    $url1 = $url . $href;
                    $name = html_entity_decode($match[2]);
                    $size = html_entity_decode($match[3]);
                    $time = html_entity_decode($match[4]);
                    $list[] = [
                        'href' => $href,
                        'url' => $url1,
                        'name' => $name,
                        'size' => $size,
                        'time' => $time,
                    ];
                    echo $name, "\t", $url1, PHP_EOL;
                    if ($size === '-') {
                        $list = array_merge($list, getList($url1));
                    }
                }
            }
        }
    }
    return $list;
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