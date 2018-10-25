<?php

namespace Ganlv\Down52PojieCn;

class FileDescription
{
    public static function extract($tree)
    {
        $result = [
            'name' => $tree['name'],
        ];
        if (isset($tree['children'])) {
            $result['children'] = static::extractArray($tree['children']);
        }
        return $result;
    }

    protected static function extractArray($tree)
    {
        $result = [];
        foreach ($tree as $item) {
            $result[] = self::extract($item);
        }
        return $result;
    }

    public static function merge($file, $descriptionObject)
    {
        if (!empty($descriptionObject['description'])) {
            $file['description'] = $descriptionObject['description'];
        }
        if (isset($file['children']) && isset($descriptionObject['children'])) {
            $file['children'] = static::mergeArray($file['children'], $descriptionObject['children']);
        }
        return $file;
    }

    protected static function mergeArray($fileArray, $descriptionObjectArray)
    {
        $descriptionObjectIndices = Helpers::keyBy($descriptionObjectArray, 'name');
        $result = [];
        foreach ($fileArray as $item) {
            if (isset($descriptionObjectIndices[$item['name']])) {
                $item = static::merge($item, $descriptionObjectIndices[$item['name']]);
            }
            $result[] = $item;
        }
        return $result;
    }
}
