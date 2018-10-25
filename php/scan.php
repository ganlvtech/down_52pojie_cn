<?php

use Ganlv\Down52PojieCn\FileDescription;
use Ganlv\Down52PojieCn\FileSystem\LocalFileSystem;
use Ganlv\Down52PojieCn\Helpers;
use Ganlv\Down52PojieCn\Serializer\JsonpSerializer;
use Ganlv\Down52PojieCn\Serializer\JsonSerializer;
use Ganlv\Down52PojieCn\Serializer\YamlSerializer;

require __DIR__ . '/vendor/autoload.php';

date_default_timezone_set('Asia/Shanghai');

$config = include __DIR__ . '/config/config.php';

$fileSystem = new LocalFileSystem([
    'BASE_PATH' => $config['BASE_PATH'],
    'EXCLUDE_FILES' => $config['EXCLUDE_FILES'],
]);
$data = $fileSystem->tree();

if (file_exists($config['DESCRIPTION_FILE'])) {
    $yml = file_get_contents($config['DESCRIPTION_FILE']);
    $serializer = new YamlSerializer();
    $descriptionData = $serializer->unserialize($yml);
    $data = FileDescription::merge($data, $descriptionData);
    Helpers::log('description.yml merged');
} else {
    $descriptionTemplate = FileDescription::extract($data);
    $serializer = new YamlSerializer();
    $yml = $serializer->serialize($descriptionTemplate);
    file_put_contents($config['DESCRIPTION_FILE'], $yml);
    Helpers::log('description.yml template generated');
}

switch ($config['OUTPUT_TYPE']) {
    case 'jsonp':
        $serializer = new JsonpSerializer([
            'CALLBACK_NAME' => $config['JSONP_CALLBACK'],
        ]);
        break;
    case 'yaml':
        $serializer = new YamlSerializer();
        break;
    default:
        $serializer = new JsonSerializer();
}
$serialized = $serializer->serialize($data);
file_put_contents($config['OUTPUT_FILE'], $serialized);
