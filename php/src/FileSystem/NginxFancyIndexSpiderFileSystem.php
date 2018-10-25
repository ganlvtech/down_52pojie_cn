<?php

namespace Ganlv\Down52PojieCn\FileSystem;

use Ganlv\Down52PojieCn\Helpers;

class NginxFancyIndexSpiderFileSystem extends ScannableFileSystem
{
    public function __construct(array $options = [])
    {
        if (empty($options['BASE_PATH'])) {
            $options['BASE_PATH'] = 'https://down.52pojie.cn';
        }
        parent::__construct($options);
    }

    protected function scanOne(string $fullPath = '')
    {
        $html = Helpers::curl($fullPath);
        if (!$html) {
            return [];
        }

        if (1 !== preg_match('#<tbody>(.*?)</tbody>#su', $html, $matches)) {
            return [];
        }

        $tbody = $matches[1];
        if (false === preg_match_all('#<tr><td.*?><a href="(.*?)".*?>(.*?)</a></td><td.*?>(.*?)</td><td.*?>(.*?)</td></tr>#su', $tbody, $matches, PREG_SET_ORDER)) {
            return [];
        }

        $list = [];
        foreach ($matches as $match) {
            $href = html_entity_decode($match[1]);
            if ($href === '../') {
                continue;
            }

            $name = html_entity_decode($match[2]);
            $size = html_entity_decode($match[3]);
            $time = html_entity_decode($match[4]);
            $isDir = ($size === '-');

            if ($isDir) {
                $item = [
                    'name' => rtrim($name, '/'),
                    'children' => [],
                ];
            } else {
                $item = [
                    'name' => $name,
                    'size' => Helpers::sizeToNumber($size),
                    'time' => strtotime($time),
                ];
            }

            $list[] = $item;
        }

        return $list;
    }
}
