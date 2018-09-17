<?php

date_default_timezone_set('Asia/Shanghai');
define('BASE_URL', 'https://down.52pojie.cn');

$origin = crawlOriginalList('/');
file_put_contents(__DIR__ . '/origin.json', json_encode($origin));

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
