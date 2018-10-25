<?php

namespace Ganlv\Down52PojieCn\Serializer;

class JsonSerializer extends AbstractSerializer
{
    public function serialize(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function unserialize(string $str)
    {
        return json_decode($str, true);
    }
}
