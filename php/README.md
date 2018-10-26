# php 文件扫描程序

扫描目录，并生成 json、jsonp、yaml 格式的文件，另外还可以为文件和文件夹附加描述信息。

## 目录结构

```plain
* config/                配置文件目录（需要自行修改）
    * config.php         扫描本地文件使用的设置
* data/                  数据文件目录（程序会储存一些数据，有些需要手动修改）
    * description.yml    文件夹/文件的描述信息，会混合到生成的 list.json 中
* src/                   主要代码目录（4 种获取文件列表的扫描器、3 输出格式的编解码器）
* vendor/
* .gitignore
* composer.json
* composer.lock
* crawl.php              自动爬取文件列表，用于构建 GitHub Pages
* README.md
* scan.php               用于扫描本地文件
```

## 使用方法

首先，复制一份配置文件

```bash
cp config/config.php.example config/config.php
```

然后，修改 `config/config.php` 中的配置参数

最后，执行扫描指令

```bash
php scan.php
```

如果 `data/description.yml` 不存在的话，首次执行 `php scan.php` 会自动生成一个 `data/description.yml` 的模板，这里面没有 `description` 字段，如果想给文件添加备注的话，需要手动在对应文件的文件名下添加一个 `description` 字段，并填写备注信息。

然后，再次执行 `php scan.php` 则会将描述信息合并到 `list.json` 中。

## 备注文件格式

`php scan.php` 自动生成的格式如下。

```yaml
name: /
children:
  -
    name: Challenge
    children:
      -
        name: 2012CM
      -
        name: 2016_Security_Challenge
```

你需要手动添加几个 `description` 字段，然后填入相关描述。

```yaml
name: /
children:
  -
    name: Challenge
    description: 收录论坛往年比赛题目
    children:
      -
        name: 2012CM
        description: 2012 年论坛举行 CrackMe 大赛的作品和对应的分析文章，难度有易、中、难，大家任选学习
      -
        name: 2016_Security_Challenge
        description: 2016 年论坛举行安全挑战赛，容纳了更多与实际应用息息相关的 Windows、移动安全相关考题，欢迎大家学习
```

## 配置文件内容

```php
<?php

return [
    // 扫描的根目录（绝对路径）
    'BASE_PATH' => '/home/ganlv/Downloads',
    // 排除的文件（相对于 BASE_PATH）
    'EXCLUDE_FILES' => [
        '/list.json',
        '/list.js',
    ],
    // 描述文件（绝对路径）
    'DESCRIPTION_FILE' => dirname(__DIR__) . '/data/description.yml',
    // 输出的文件类型（json 或者 jsonp）
    'OUTPUT_TYPE' => 'jsonp',
    // 输出的文件路径（绝对路径）
    'OUTPUT_FILE' => '/home/ganlv/Downloads/public/.list.js',
    // jsonp 回调函数名（需要与 index.html 中的设置相同）
    'JSONP_CALLBACK' => '__jsonpCallbackDown52PojieCn',
];
```

## 爬虫

执行爬虫脚本（有缓存）

```bash
php php/crawl.php
```

缓存顺序：

1. https://raw.githubusercontent.com/ganlvtech/down_52pojie_cn/gh-pages/list.json
2. https://down.52pojie.cn/list.json
3. https://down.52pojie.cn/list.js
4. 爬取文件列表

注意：爬虫脚本并没有读取任何配置文件，所有内容都是直接在 crawl.php 中写好的。

## LICENSE

The MIT License (MIT)

Copyright (c) 2018 Ganlv

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.
