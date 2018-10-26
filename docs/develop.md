# 本地开发说明

本项目使用 Vue CLI 3 创建，通过 Travis CI 自动构建。

## 获取文件列表

把 `php/crawl.php` 最后一行的 `/dist/list.json` 修改成 `/public/list.json`

然后执行爬虫脚本（有缓存）

```bash
php php/crawl.php
```

## 启动开发 Webpack 服务器

```bash
npm install
npm run serve
```

支持 Hot reload。访问 <http://localhost:8080/> 进行调试。

## 通过 Travis CI 编译成静态网页并发布到 GitHub Pages

```bash
export BASE_URL=/down_52pojie_cn/
npm install
npm run build
php php/crawl.php
```

说明：

1. 设置环境变量 BASE_URL 为 GitHub Pages 的根目录
2. 通过 `npm run build` 构建页面
3. 爬取文件列表（有缓存）

还需要在 `dist/index.html` 中，Down52Pojie 的配置中添加一条 `jsonUrl: '/down_52pojie_cn/list.json'`。

此时 `dist` 文件夹直接部署到 GitHub Pages 即可

具体可以参考 `build/build_github_pages.sh`。

## 构建 Release 版本

```bash
export USE_FANCY_INDEX=true
export ASSETS_DIR=.fancyindex
npm install
npm run build
```

说明：

1. 打开 `USE_FANCY_INDEX` 并且设置输出文件夹为 `.fancyindex`
2. 通过 `npm run build` 构建页面
3. 此时 `dist` 文件夹的内容直接放到网站根目录即可
4. 此版本会在 `index.html` 中插入 Vue Router 使用 history 模式的配置。

具体可以参考 `build/build_release.sh`。
