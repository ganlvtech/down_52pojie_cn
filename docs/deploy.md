# 配置说明

有 3 种部署方案，

1. 为了优化网站 SEO，使用 [ngx-fancyindex](https://github.com/aperezdc/ngx-fancyindex)
2. 不考虑 SEO 问题，希望 URL 看着比较美观
3. 单页应用

第 1 种：ngx-fancyindex 可以直接在 HTML 中生成文件列表，这对搜索引擎十分友好，但是对用户不好。

其余两种都不需要 ngx-fancyindex，这两种的区别在于使用了 Vue Router 的两种模式，`history` 或者 `hash`。

第 2 种：使用 history 模式，URL 是 `https://down.52pojie.cn/Tools/Debuggers/`，这种需要对 Nginx 或 Apache 进行一些配置。

第 3 种：使用 hash 模式，URL 是 `https://down.52pojie.cn/#/Tools/Debuggers/`，这种直接上传到服务器上即可，无需过多配置。

本程序可以不依赖 ngx-fancyindex，可以单独使用，可以作为静态页面使用，例如本项目的 [GitHub Pages](https://ganlvtech.github.io/down_52pojie_cn/)。

## 使用 ngx-fancyindex

### 安装 ngx-fancyindex

在服务器安装 ngx-fancyindex

```bash
sudo apt-get install nginx-extras
```

### 下载并解压本仓库的文件

在 [本仓库 Release 页面](https://github.com/ganlvtech/down_52pojie_cn/releases) 下载 `dist.zip` 的压缩包，解压到网站根目录。

此时网站的目录结构应该是

```plain
/
    foo.zip
    bar.exe
    ......
    .fancyindex/
        header.html
        footer.html
        js/
        css/
```

### 修改 Nginx 配置文件

然后修改 `/etc/nginx/site-enables/your-site`，在其中增加 ngx-fancyindex 的配置。示例配置文件如下。

```nginx
server {
    listen       80;
    listen       443 ssl;
    root         /srv/www/down;
    index        index.html index.htm;
    server_name  down.test;

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

应该可以看到网站的主页已经变成了本项目的样子了，但是现在暂时还没有文件列表。生成文件列表请参考 [php 文件扫描程序说明文档](php/README.md)。

## 使用 history 模式

## 网站的目录结构

```plain
/
    foo.zip
    bar.exe
    ......
    index.html
    .fancyindex/
        js/
        css/
```

### Nginx 配置

```nginx
server {
    listen       80;
    listen       443 ssl;
    root         /srv/www/down;
    index        /index.html;
    server_name  down.test;
}
```

### Apache 配置

打开 Rewrite 模块

```bash
sudo a2enmod rewrite
```

新建 `/etc/apache2/sites-enabled/down.test.conf`

```apache
<VirtualHost *:80>
    ServerName down.test
    DocumentRoot /srv/www/down

    <Directory "/srv/www/down">
        Options FollowSymlinks
        AllowOverride None
        Require all granted

        RewriteEngine On
        RewriteBase /
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^(.*)$ /index.html [L]
    </Directory>
</VirtualHost>
```

### 插件配置

```javascript
window.down52PojieCn = new Down52PojieCn({
    routerMode: 'history'
});
```

## 使用 hash 模式

### 网站的目录结构

```plain
/
    foo.zip
    bar.exe
    ......
    index.html
    .fancyindex/
        js/
        css/
```

### Nginx 配置

```nginx
server {
    listen       80;
    listen       443 ssl;
    root         /srv/www/down;
    index        index.html;
    server_name  down.test;
}
```

### 插件配置

```javascript
window.down52PojieCn = new Down52PojieCn({});
```
