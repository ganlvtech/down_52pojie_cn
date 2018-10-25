<?php

namespace Ganlv\Down52PojieCn\FileSystem;

use Ganlv\Down52PojieCn\Helpers;
use Ganlv\Down52PojieCn\Serializer\JsonpSerializer;
use Ganlv\Down52PojieCn\Serializer\JsonSerializer;

class DownloadFileSystem extends AbstractFileSystem
{
    protected $fileUrl;

    public function __construct(array $options = [])
    {
        parent::__construct($options);
        $this->fileUrl = $options['FILE_URL'] ?? 'https://down.52pojie.cn/list.js';
    }

    public function tree()
    {
        Helpers::log("Start downloading {$this->fileUrl} .");
        $json = Helpers::curl($this->fileUrl);
        Helpers::log("Download finished.");
        $serializer = new JsonSerializer();
        $data = $serializer->unserialize($json);
        if (!is_null($data)) {
            return $data;
        }
        $serializer = new JsonpSerializer();
        $data = $serializer->unserialize($json);
        return $data;
    }
}
