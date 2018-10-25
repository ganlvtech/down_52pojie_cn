<?php

namespace Ganlv\Down52PojieCn\FileSystem;

abstract class AbstractFileSystem
{
    public function __construct(array $options = [])
    {
    }

    /**
     * 获取指定目录下的全部数据
     *
     * @return array|null Example: [
     *                        'name' => '/',
     *                        'children' => [
     *                            [
     *                                'name' => 'foo',
     *                                'size' => 123,
     *                                'time' => 1530000000,
     *                            ],
     *                            [
     *                                'name' => 'foo',
     *                                'children' => [
     *                                    [
     *                                        'name' => 'bar',
     *                                        'size' => 456,
     *                                        'time' => 1520000000,
     *                                    ],
     *                                ],
     *                            ],
     *                        ],
     *                    ];
     */
    public abstract function tree();
}
