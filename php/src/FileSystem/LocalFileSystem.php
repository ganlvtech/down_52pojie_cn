<?php

namespace Ganlv\Down52PojieCn\FileSystem;

class LocalFileSystem extends ScannableFileSystem
{
    protected function scanOne(string $dirFullPath)
    {
        $result = [];
        foreach (scandir($dirFullPath) as $name) {
            if ($name === '.' || $name === '..') {
                continue;
            }
            $fullPath = "{$dirFullPath}/{$name}";
            if (is_dir($fullPath)) {
                $result[] = [
                    'name' => $name,
                    'children' => [],
                ];
            } else {
                $result[] = [
                    'name' => $name,
                    'size' => filesize($fullPath),
                    'time' => filemtime($fullPath),
                ];
            }
        }
        return $result;
    }
}
