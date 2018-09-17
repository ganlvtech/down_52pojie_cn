# 爱盘 down.52pojie.cn

<https://down.52pojie.cn/>

[![Build Status](https://travis-ci.org/ganlvtech/down_52pojie_cn.svg?branch=master)](https://travis-ci.org/ganlvtech/down_52pojie_cn)

本仓库为 [吾爱破解论坛](https://www.52pojie.cn/) [爱盘](https://down.52pojie.cn/) 页面的源代码，目前为第 2 版，使用 Vue 重构，新增加搜索功能。

相关链接

* [在线演示](https://ganlvtech.github.io/down_52pojie_cn/)
* [1.0 版本](https://github.com/ganlvtech/down_52pojie_cn/tree/1.0)
* [下载 Release 版本](https://github.com/ganlvtech/down_52pojie_cn/releases)

## 部署

在 [Release 页面](https://github.com/ganlvtech/down_52pojie_cn/releases) 下载 `dist.zip` 的压缩包，解压到服务器根目录。

在服务器安装 [ngx-fancyindex](https://github.com/aperezdc/ngx-fancyindex)，然后 ngx-fancyindex 配置方法如下。

```nginx
location / {
    fancyindex         on;
    fancyindex_header  "/.fancyindex/header.html";
    fancyindex_footer  "/.fancyindex/footer.html";
}
```

推荐将 `scan.php` 移动到服务器的目录以外，防止被他人访问。

修改 `scan.php` 中的配置，示例配置如下，推荐使用绝对路径。

```php
<?php
// ==================== config ====================

define('BASE_DIR', '/home/ganlv/Downloads');
define('OUTPUT_FILE', BASE_DIR . '/list.json');
$exclude_files = [
    '/.fancyindex',
    '/list.json',
];
```

执行 `php scan.php`，即可生成 `list.json`。

用户访问页面时，会请求 `list.json`，请求后面会带一个时间戳参数（例如：`t=153xxxxxxx`），这个时间戳为当天的 UTC 时间 0 点的时间戳，通过这样可以保证浏览器或 CDN 缓存最长是 1 天。

`list.json` 需要放在网站根目录下，可以搜索 `app.js` 中的 `/list.json` 字符串，替换成其他路径。

## 编译

### 开发环境

```bash
php php/crawl.php
php php/format.php
npm install
npm run serve
```

说明：通过 `php/crawl.php` 爬取爱盘文件列表，通过 `php/format.php` 生成 `list.json`，然后通过 `npm run serve` 启动 Webpack 服务器，Vue Router 使用 hash 方式。

访问 <http://localhost:8080/> 进行调试。

### GitHub Pages 通过 Travis CI 编译

```bash
php php/crawl.php
php php/format.php
npm install
npm run build
```

说明：通过 `php/crawl.php` 爬取爱盘文件列表，通过 `php/format.php` 生成 `list.json`，然后通过 `npm run build` 构建页面，此时 `dist` 文件夹直接部署到 GitHub Pages 即可，Vue Router 使用 hash 方式。

具体可以参考 `.travis.yml`。

### ngx-fancyindex 版本

构建过程

```bash
npm install
echo "VUE_APP_ROUTER_MODE=history" > .env.local
echo "USE_FANCY_INDEX=true" >> .env.local
echo "FANCY_INDEX_DIR=.fancyindex" >> .env.local
npm run build
```

说明：通过 `npm run build` 构建页面，此时 `dist` 文件夹直接放到网站根目录即可。Vue Router 使用 history 方式。

具体可以参考 `build_release.sh`。

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