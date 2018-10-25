<?php

namespace Ganlv\Down52PojieCn\Serializer;

use Symfony\Component\Yaml\Yaml;

class YamlSerializer extends AbstractSerializer
{
    public function serialize(array $data): string
    {
        return Yaml::dump($data, 20, 2);
    }

    public function unserialize(string $str)
    {
        return Yaml::parse($str);
    }
}
