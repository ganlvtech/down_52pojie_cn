<?php

namespace Ganlv\Down52PojieCn;

class Helpers
{
    public static function curl($url)
    {
        $errno = 1;
        $content = '';
        $retry = 0;
        while ($errno > 0 && $retry++ < 3) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $content = curl_exec($ch);
            $errno = curl_errno($ch);
            if ($errno > 0) {
                $error = curl_error($ch);
                curl_close($ch);
                Helpers::log("cURL error ($errno): $error.");
            }
            curl_close($ch);
        }
        return $content;
    }

    public static function log($message)
    {
        echo $message, PHP_EOL;
    }

    public static function sizeToNumber($size)
    {
        if (static::endsWith($size, 'GiB')) {
            return (float)$size * 1024 * 1024 * 1024;
        } elseif (static::endsWith($size, 'MiB')) {
            return (float)$size * 1024 * 1024;
        } elseif (static::endsWith($size, 'KiB')) {
            return (float)$size * 1024;
        } elseif (static::endsWith($size, 'GB')) {
            return (float)$size * 1000 * 1000 * 1000;
        } elseif (static::endsWith($size, 'MB')) {
            return (float)$size * 1000 * 1000;
        } elseif (static::endsWith($size, 'KB')) {
            return (float)$size * 1000;
        } else {
            return (float)$size;
        }
    }

    /** @link https://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php/834355#834355 */
    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
        return (substr($haystack, -$length) === $needle);
    }

    public static function keyBy($collection, $keyBy)
    {
        $results = [];
        foreach ($collection as $item) {
            $resolvedKey = $item[$keyBy];
            if (is_object($resolvedKey)) {
                $resolvedKey = (string)$resolvedKey;
            }
            $results[$resolvedKey] = $item;
        }
        return $results;
    }

}
