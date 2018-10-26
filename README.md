# 爱盘 down.52pojie.cn

[![Build Status](https://travis-ci.org/ganlvtech/down_52pojie_cn.svg?branch=master)](https://travis-ci.org/ganlvtech/down_52pojie_cn)

本仓库为 [吾爱破解论坛](https://www.52pojie.cn/) [爱盘](https://down.52pojie.cn/) 页面的源代码，目前为第 2 版，使用 Vue 重构，新增加搜索功能。

相关链接

* [在线演示](https://ganlvtech.github.io/down_52pojie_cn/)
* [1.0 版本](https://github.com/ganlvtech/down_52pojie_cn/tree/1.0)
* [下载 Release 版本](https://github.com/ganlvtech/down_52pojie_cn/releases)

## 部署

### 安装 ngx-fancyindex

> 本程序可以不依赖 ngx-fancyindex 单独使用，甚至可以作为静态页面使用，例如本项目的 [GitHub Pages](https://ganlvtech.github.io/down_52pojie_cn/)。

在服务器安装 [ngx-fancyindex](https://github.com/aperezdc/ngx-fancyindex)

```bash
sudo apt-get install nginx-extras
```

### 下载并解压本仓库的文件

在 [本仓库 Release 页面](https://github.com/ganlvtech/down_52pojie_cn/releases) 下载 `dist.zip` 的压缩包，解压到网站根目录。

### 修改 Nginx 配置文件

然后修改 `/etc/nginx/site-enables/your-site`，在其中增加 ngx-fancyindex 的配置。示例配置文件如下。

```nginx
server {
    listen       80;
    listen       443 ssl;
    root         /srv/www/down;
    index        index.html index.htm;
    server_name  down.localhost;

    location / {
        fancyindex         on;
        fancyindex_header  "/.fancyindex/header.html";
        fancyindex_footer  "/.fancyindex/footer.html";
    }
}
```

> `ngx-fancyindex` 默认不会列举出以 `.` 开头的文件夹和文件。

这时执行

```bash
sudo service nginx reload
```

应该可以看到网站的主页已经变成了本项目的样子了，但是现在暂时还没有文件列表。

### 生成服务器文件列表

将 `scan.php` 移动到网站根目录以外，防止被他人访问。

修改 `scan.php` 前几行的配置，推荐使用绝对路径。示例配置如下。

```php
<?php
// ==================== config ====================

define('BASE_DIR', '/srv/www/down');
define('OUTPUT_FILE', BASE_DIR . '/list.json');
$exclude_files = [
    '/list.json',
];
```

如果你不想使用 ajax 请求 json，本项目也支持使用 jsonp 加载列表。

把 `OUTPUT_FILE` 的扩展名改成 `.js` 即可生成支持 jsonp 加载的文件。

```php
<?php
// ==================== config ====================

define('BASE_DIR', '/srv/www/down');
define('OUTPUT_FILE', BASE_DIR . '/list.js');
$exclude_files = [
    '/list.js',
];
```

> `scan.php` 默认不会列举出以 `.` 开头的文件和文件夹。

然后执行 `php scan.php`，即可生成 `list.json`。

### 配置说明

本项目已经插件化，可以使用如下代码，使用默认配置加载插件。

```javascript
window.down52PojieCn = new Down52PojieCn();
```

上面代码等效于

```js
window.down52PojieCn = new Down52PojieCn({
    vueElement: '#app';
    routerMode: 'hash',
    baseUrl: 'https://down.52pojie.cn',
    requestType: 'json',
    jsonUrl: '/list.json',
    cacheTime: 0
});
```


如果使用 jsonp 的话可以使用如下代码。

```js
window.down52PojieCn = new Down52PojieCn({
    requestType: 'jsonp'
});
```

等效于

```js
window.down52PojieCn = new Down52PojieCn({
    vueElement: '#app';
    routerMode: 'hash',
    baseUrl: 'https://down.52pojie.cn',
    requestType: 'jsonp',
    jsonpUrl: '/list.js',
    jsonpCallback: '__jsonpCallbackDown52PojieCn',
    cacheTime: 0
});
```

对于已开启 ngx-fancyindex 的网站，可以将 `routerMode` 修改为 `'history'`。

`dist.zip` 中已经配置成 history + jsonp 模式了。

#### 默认配置说明

* Vue Router 默认使用 hash 方式。
* 默认使用 ajax 请求 json 文件
* 默认从网站根目录加载 `list.json`
* 默认不会使用缓存（缓存控制交给服务器管理）

具体配置说明可以参考 `src/Down52PojieCn.js` 中的详细注释。

#### 关于缓存

用户访问页面时，会请求 `list.json`，请求后面会带一个时间戳参数（例如：`t=153xxxxxxx`），这个时间戳为向 `cacheTime` 取整后的数字。

#### 文件路径

由于单页应用的特殊性，所有文件的路径必须从网站根目录开始写，比如可以写 `/list.json` 而不能写 `list.json` 或 `./list.json`。

## 编译

本项目使用 Vue CLI 3 创建，通过 Travis CI 自动构建。

### 开发环境

```bash
php php/crawl.php
php php/format.php
npm install
npm run serve
```

说明：

1. 通过 `php/crawl.php` 爬取爱盘文件列表
2. 通过 `php/format.php` 生成 `list.json`
3. 然后通过 `npm run serve` 启动 Webpack 服务器，支持 Hot reload。
4. 访问 <http://localhost:8080/> 进行调试。

### 通过 Travis CI 编译成静态网页并发布到 GitHub Pages

```bash
php php/crawl.php
php php/format.php
npm install
echo "BUILD_GITHUB_PAGES=true" > .env.local
npm run build
```

说明：

1. 打开 `BUILD_GITHUB_PAGES` 选项
2. 通过 `npm run build` 构建页面
3. 此时 `dist` 文件夹直接部署到 GitHub Pages 即可

具体可以参考 `build_github_pages.sh`。

### 构建 ngx-fancyindex 发布版本

```bash
npm install
echo "USE_FANCY_INDEX=true" >> .env.local
npm run build
```

说明：

1. 打开 `USE_FANCY_INDEX` 并且设置输出文件夹为 `FANCY_INDEX_DIR` 变量（`FANCY_INDEX_DIR` 在 `.env` 中设置）
2. 通过 `npm run build` 构建页面
3. 此时 `dist` 文件夹的内容直接放到网站根目录即可
4. 此版本会在 `index.html` 中插入 Vue Router 使用 history 模式的配置。

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
