<?php

namespace Ganlv\Down52PojieCn\FileSystem;

use Ganlv\Down52PojieCn\Helpers;

abstract class ScannableFileSystem extends AbstractFileSystem
{
    protected $basePath;
    protected $excludeFiles;

    public function __construct(array $options = [])
    {
        $this->basePath = $options['BASE_PATH'];
        $this->basePath = rtrim($this->basePath, '/');
        $this->excludeFiles = $options['EXCLUDE_FILES'] ?? [];
        foreach ($this->excludeFiles as &$file) {
            $file = trim($file, '/');
        }
    }

    public function tree(): array
    {
        return [
            'name' => '/',
            'children' => $this->scanRecursive(),
        ];
    }

    /**
     * @param string $dirRelativePath
     *
     * @return array
     */
    protected function scanRecursive(string $dirRelativePath = '')
    {
        $result = [];
        $dirRelativePath = trim($dirRelativePath, '/');
        $dirFullPath = "{$this->basePath}/{$dirRelativePath}";
        Helpers::log("Scan: $dirFullPath");
        foreach ($this->scanOne($dirFullPath) as $item) {
            $itemRelativePath = "{$dirRelativePath}/{$item['name']}";
            if ($this->isExcluded($itemRelativePath)) {
                continue;
            }
            if (isset($item['children'])) {
                $item['children'] = $this->scanRecursive($itemRelativePath);
            }
            $result[] = $item;
        }
        return $result;
    }

    protected function isExcluded($relativePath)
    {
        $relativePath = trim($relativePath, '/');
        return in_array($relativePath, $this->excludeFiles);
    }

    /**
     * @param string $dirFullPath
     *
     * @return mixed [
     *                   [
     *                       'name' => 'foo',
     *                       'size' => 123,
     *                       'time' => 1530000000,
     *                   ],
     *                   [
     *                       'name' => 'foo',
     *                       'children' => [],
     *                   ],
     *               ];
     */
    abstract protected function scanOne(string $dirFullPath);
}
