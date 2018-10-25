<?php

namespace Ganlv\Down52PojieCn\Serializer;

abstract class AbstractSerializer
{
    public function __construct(array $options = [])
    {
    }

    public abstract function serialize(array $data): string;

    public abstract function unserialize(string $str);
}
