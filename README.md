# 爱盘 down.52pojie.cn

一个在线文件列表浏览器。[在线演示](https://ganlvtech.github.io/down_52pojie_cn/)。

[![Build Status](https://travis-ci.org/ganlvtech/down_52pojie_cn.svg?branch=master)](https://travis-ci.org/ganlvtech/down_52pojie_cn)

本仓库为 [吾爱破解论坛](https://www.52pojie.cn/) [爱盘](https://down.52pojie.cn/) 页面的源代码。

使用 [Vue.js](https://vuejs.org/) + [Vue Router](https://router.vuejs.org/) 方式，由 [Vue CLI 3](https://cli.vuejs.org/) 构建。

[下载 Release 版本](https://github.com/ganlvtech/down_52pojie_cn/releases)

## 特点

* 单页应用
  * 一次性加载，响应迅速
  * 支持通过导航栏定位
  * 支持浏览器前进、后退
* 静态页面
  * 一次性列举出服务器文件列表，之后不用每次都列举文件
  * 服务器支持静态页面即可，可部署与 GitHub Pages
  * 页面可以保存
* 搜索文件
  * 普通搜索
  * 支持模糊搜索
  * 支持正则表达式搜索
* 可以为文件或文件夹添加描述

**注意：**如果你的服务器文件过多，不适合一次性列举出全部文件，那么最好不要使用本应用。

## 部署

请参考 [部署说明](docs/deploy.md)

## 生成服务器文件列表

请参考 [php 文件扫描程序说明文档](php/README.md)

## 前端配置说明

请参考 [前端配置说明](docs/config.md)

## 其他链接

* [之前未使用 Vue.js 版本](https://github.com/ganlvtech/down_52pojie_cn/tree/1.0)
* 类似项目：
    * [GitHub Topic: file-explorer](https://github.com/topics/file-explorer)
    * [lrsjng/h5ai](https://github.com/lrsjng/h5ai): HTTP web server index for Apache httpd, lighttpd, nginx and Cherokee. <https://larsjung.de/h5ai/>

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
