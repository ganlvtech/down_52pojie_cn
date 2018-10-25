<?php

namespace Ganlv\Down52PojieCn\Serializer;

class JsonpSerializer extends AbstractSerializer
{
    protected $callbackName;

    public function __construct(array $options = [])
    {
        parent::__construct($options);
        $this->callbackName = $options['CALLBACK_NAME'] ?? '__jsonpCallbackDown52PojieCn';
    }

    public function serialize(array $data): string
    {
        $jsonSerializer = new JsonSerializer();
        $json = $jsonSerializer->serialize($data);
        return "{$this->callbackName}({$json});";
    }

    public function unserialize(string $str)
    {
        if (1 === preg_match('/^.*?\(([\s\S]*)\);\s*$/', $str, $matches)) {
            $jsonSerializer = new JsonSerializer();
            return $jsonSerializer->unserialize($matches[1]);
        }
        return null;
    }
}
