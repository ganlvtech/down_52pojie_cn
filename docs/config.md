# 前端配置说明

本项目已经插件化，在 HTML 中插入下列代码，即可以使用如下代码，使用默认配置加载插件。

```html
<div id="app"></div>
<script>
    window.down52PojieCn = new Down52PojieCn();
</script>
```

## 默认配置

上面代码等效于

```js
window.down52PojieCn = new Down52PojieCn({
    vueElement: '#app',
    routerMode: 'hash',
    baseUrl: 'https://down.52pojie.cn',
    requestType: 'json',
    jsonUrl: '/list.json',
    cacheTime: 0
});
```

## 使用 jsonp 加载文件列表

```js
window.down52PojieCn = new Down52PojieCn({
    requestType: 'jsonp'
});
```

等效于

```js
window.down52PojieCn = new Down52PojieCn({
    vueElement: '#app',
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

## 默认配置说明

* Vue Router 默认使用 hash 方式。
* 默认使用 ajax 请求 json 文件
* 默认从网站根目录加载 `list.json`
* 默认不会使用缓存（缓存控制交给服务器管理）

具体配置说明可以参考 `src/Down52PojieCn.js` 中的详细注释。

## 关于缓存

如果启用缓存（`cacheTime` 设为非 0 的数），用户访问页面时，会请求 `list.json`，请求后面会带一个时间戳参数（例如：`t=153xxxxxxx`），这个时间戳为向 `cacheTime` 取整后的数字。

## 文件路径

由于单页应用的特殊性，所有文件的路径必须从网站根目录开始写，比如可以写 `/list.json` 而不能写 `list.json` 或 `./list.json`。
