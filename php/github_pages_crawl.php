<?php

use Ganlv\Down52PojieCn\FileDescription;
use Ganlv\Down52PojieCn\FileSystem\DownloadFileSystem;
use Ganlv\Down52PojieCn\FileSystem\GitHubCommitFileSystem;
use Ganlv\Down52PojieCn\FileSystem\NginxFancyIndexSpiderFileSystem;
use Ganlv\Down52PojieCn\Helpers;
use Ganlv\Down52PojieCn\Serializer\JsonSerializer;
use Ganlv\Down52PojieCn\Serializer\YamlSerializer;

require __DIR__ . '/vendor/autoload.php';

date_default_timezone_set('Asia/Shanghai');

$fileSystem = new GitHubCommitFileSystem([
    'COMMITS_URL' => 'https://github.com/ganlvtech/down_52pojie_cn/commits/gh-pages/list.json',
    'RAW_FILE_URL' => 'https://raw.githubusercontent.com/ganlvtech/down_52pojie_cn/gh-pages/list.json',
    'CACHE_MAX_AGE' => 7 * 24 * 60 * 60,
]);
$data = $fileSystem->tree();

if (!$data) {
    $fileSystem = new DownloadFileSystem([
        'FILE_URL' => 'https://down.52pojie.cn/list.json',
    ]);
    $data = $fileSystem->tree();
}

if (!$data) {
    $fileSystem = new DownloadFileSystem([
        'FILE_URL' => 'https://down.52pojie.cn/list.js',
    ]);
    $data = $fileSystem->tree();
}

if (!$data) {
    $fileSystem = new NginxFancyIndexSpiderFileSystem([
        'BASE_PATH' => 'https://down.52pojie.cn',
    ]);
    $data = $fileSystem->tree();
}

$descriptionFile = __DIR__ . '/data/description.yml';
if (file_exists($descriptionFile)) {
    $yml = file_get_contents($descriptionFile);
    $serializer = new YamlSerializer();
    $descriptionData = $serializer->unserialize($yml);
    $data = FileDescription::merge($data, $descriptionData);
    Helpers::log('description.yml merged');
} else {
    $descriptionTemplate = FileDescription::extract($data);
    $serializer = new YamlSerializer();
    $yml = $serializer->serialize($descriptionTemplate);
    file_put_contents($descriptionFile, $yml);
    Helpers::log('description.yml template generated');
}

$serializer = new JsonSerializer();
$json = $serializer->serialize($data);
file_put_contents(dirname(__DIR__) . '/dist/list.json', $json);
