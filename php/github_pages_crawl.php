<?php

// Load cache

// https://raw.githubusercontent.com/ganlvtech/down_52pojie_cn/gh-pages/list.json

echo 'Get history for down_52pojie_cn/list.json on branch gh-pages.', PHP_EOL;
$html = curl('https://github.com/ganlvtech/down_52pojie_cn/commits/gh-pages/list.json');

if (preg_match_all('/Commits on (\w+? \d+?, \d{4})/', $html, $matches)) {
    $date = $matches[1][0];
    echo 'Most recently committed on: ', $date, PHP_EOL;

    $timestamp = strtotime($date);
    $time_diff = time() - $timestamp;
    $days = $time_diff / (24 * 60 * 60);
    if ($days <= 0) {
        $daysForHumans = 'recently';
    } elseif ($days > 1) {
        $daysForHumans = (int)$days . ' days ago';
    } else {
        $daysForHumans = 'today';
    }
    echo 'Committed ', $daysForHumans, PHP_EOL;

    if ($days < 7) {
        echo 'Less than 7 days. Not expired. Start downloading.', PHP_EOL;
        $json = curl('https://raw.githubusercontent.com/ganlvtech/down_52pojie_cn/gh-pages/list.json');
        file_put_contents(dirname(__DIR__) . '/public/list.json', $json);
        echo 'Download list.json finished.', PHP_EOL;
        return;
    } else {
        echo 'File expired.', PHP_EOL;
    }
} else {
    echo 'No commits found.', PHP_EOL;
}



echo PHP_EOL;

// https://down.52pojie.cn/list.json

echo 'Try get https://down.52pojie.cn/list.json .', PHP_EOL;
$json = curl('https://down.52pojie.cn/list.json');
$list = json_decode($json, true);

if ($list) {
    echo 'Use https://down.52pojie.cn/list.json .', PHP_EOL;
    file_put_contents(dirname(__DIR__) . '/public/list.json', $json);
    echo 'Download list.json finished.', PHP_EOL;
    return;
} else {
    echo 'https://down.52pojie.cn/list.json json decode failed.', PHP_EOL;
}



echo PHP_EOL;

// https://down.52pojie.cn/list.js

echo 'Try get https://down.52pojie.cn/list.js .', PHP_EOL;
$jsonp = curl('https://down.52pojie.cn/list.js');

if (1 === preg_match('/\((.*)\);/su', $jsonp, $matches)) {
    $json = $matches[1];
    $list = json_decode($json, true);

    if ($list) {
        echo 'Use https://down.52pojie.cn/list.js .', PHP_EOL;
        file_put_contents(dirname(__DIR__) . '/public/list.json', $json);
        echo 'Download list.json finished.', PHP_EOL;
        return;
    } else {
        echo 'https://down.52pojie.cn/list.js json decode failed.', PHP_EOL;
    }
} else {
    echo 'https://down.52pojie.cn/list.js jsonp decode failed.', PHP_EOL;
}



echo PHP_EOL;

// Crawl

echo 'Start crawling original site.', PHP_EOL;
include __DIR__ . '/crawl.php';
echo 'Crawling finished.', PHP_EOL;

include __DIR__ . '/format.php';
echo 'list.json generated.', PHP_EOL;


function curl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $content = curl_exec($ch);
    curl_close($ch);
    return $content;
}
