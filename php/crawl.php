<?php

date_default_timezone_set('Asia/Shanghai');
define('BASE_URL', 'https://down.52pojie.cn');

$origin = crawlOriginalList('/');
file_put_contents(__DIR__ . '/origin.json', json_encode($origin));

function curl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $content = curl_exec($ch);
    $errno = curl_errno($ch);
    if ($errno > 0) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("cURL error ($errno): $error.");
    }
    curl_close($ch);
    return $content;
}

function crawlOriginalList($url)
{
    $retry = 0;
    $html = '';
    while (!$html && $retry++ < 3) {
        try {
            $html = curl(BASE_URL . $url . '?t=' . time());
        } catch (Exception $e) {
            echo $e->getMessage(), PHP_EOL;
        }
        sleep(1);
    }
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
                    if ($size === '-') {
                        echo urldecode($url1), PHP_EOL;
                        $item['children'] = crawlOriginalList($url1);
                    }
                    $list[] = $item;
                }
            }
        }
    }
    return $list;
}
