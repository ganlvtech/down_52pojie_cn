<?php

echo 'Get history for down_52pojie_cn/list.json on branch gh-pages', PHP_EOL;
$html = file_get_contents('https://github.com/ganlvtech/down_52pojie_cn/commits/gh-pages/list.json');

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
        $json = file_get_contents("https://raw.githubusercontent.com/ganlvtech/down_52pojie_cn/gh-pages/list.json");
        file_put_contents(dirname(__DIR__) . '/public/list.json', $json);
        echo 'Download list.json finished.', PHP_EOL;
        return;
    } else {
        echo 'File expired.', PHP_EOL;
    }
} else {
    echo 'No commits found.', PHP_EOL;
}

echo 'Start crawling original site.', PHP_EOL;
include __DIR__ . '/crawl.php';
echo 'Crawling finished.', PHP_EOL;

include __DIR__ . '/format.php';
echo 'list.json generated.', PHP_EOL;
