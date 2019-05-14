/*!
 * down.52pojie.cn (https://github.com/ganlvtech/down_52pojie_cn)
 * Copyright (c) 2018 Ganlv (https://github.com/ganlvtech)
 * Licensed under MIT (https://github.com/ganlvtech/down_52pojie_cn/blob/master/LICENSE)
 */
import axios from 'axios';
import jQuery from 'jquery';
import toastr from 'toastr';
import Vue from 'vue';
import Router from 'vue-router';
import Home from './views/Home.vue';
import App from './App.vue';
import {cacheTimestamp} from './helpers';

const $ = jQuery;

Vue.config.productionTip = false;
Vue.use(Router);

export default function Down52PojieCn(config = {}) {
    /**
     * Vue 实例的挂载目标
     *
     * @default '#app'
     * @type string
     * @see https://cn.vuejs.org/v2/api/#el
     */
    this.vueElement = config.vueElement || '#app';

    /**
     * Vue Router 历史记录模式
     *
     * 'hash' or 'history'
     *
     * @default 'hash'
     * @type string
     * @see https://router.vuejs.org/api/#router-mode
     */
    this.routerMode = config.routerMode || 'hash';

    /**
     * 显示在导航栏上的最前面的网址、文件下载的基础网址（末尾不加斜线）
     *
     * @type string
     * @default 'https://down.52pojie.cn'
     */
    this.baseUrl = config.baseUrl || 'https://down.52pojie.cn';

    /**
     * 请求数据文件的方法
     *
     * 'json' or 'jsonp'
     *
     * @default 'json'
     * @type string
     */
    this.requestType = config.requestType || 'json';

    /**
     * json 文件的 URL
     *
     * @type string
     * @default '/list.json'
     */
    this.jsonUrl = config.jsonUrl || '/list.json';

    /**
     * jsonp 请求的 URL
     *
     * @type string
     * @default '/list.js'
     */
    this.jsonpUrl = config.jsonpUrl || '/list.js';

    /**
     * jsonp 请求的回调函数名，
     *
     * @type string
     * @default '__jsonpCallbackDown52PojieCn'
     */
    this.jsonpCallback = config.jsonpCallback || '__jsonpCallbackDown52PojieCn';

    /**
     * 数据文件缓存时间
     *
     * 数字表示缓存时间（秒），请求后面会加上类似 ?t=153xxxxxxx 的时间戳，这个时间戳是通过整除得到的，通常是可以通过 CDN 缓存的。
     * falsy value 表示不加 ?t= 的时间戳，是否读取缓存靠服务器的缓存控制
     *
     * @type {int}
     * @default 0
     */
    this.cacheTime = config.cacheTime || 0;

    this.router = null;
    toastr.info('爱盘搜索扩展插件加载完成，正在加载文件列表');
    this[this.requestType]();
}

Down52PojieCn.prototype.json = function () {
    let url = this.jsonUrl;
    if (this.cacheTime) {
        url += '?t=' + cacheTimestamp(this.cacheTime);
    }
    axios.get(url)
        .then(response => {
            let data = response.data;
            this.callback(data);
        })
        .catch(error => {
            toastr.error(`通过 ajax 加载文件出错，请刷新页面或联系管理员。错误信息：${error.message}`);
            throw error;
        });
};

Down52PojieCn.prototype.jsonp = function () {
    window[this.jsonpCallback] = data => {
        clearTimeout(timeout);
        this.callback(data);
        window[this.jsonpCallback] = undefined;
    };
    let script = document.createElement('script');
    script.src = this.jsonpUrl;
    document.getElementsByTagName('head')[0].appendChild(script);
    let timeout = setTimeout(function () {
        toastr.error('通过 jsonp 加载文件列表超时，请刷新页面或联系管理员。');
    }, 20000);
};

Down52PojieCn.prototype.callback = function (data) {
    let router = new Router({
        mode: this.routerMode,
        base: '/',
        routes: [
            {
                path: '*',
                name: 'home',
                component: Home,
                props: {
                    baseUrl: this.baseUrl,
                    list: data
                }
            }
        ]
    });
    this.router = router;

    this.vm = new Vue({
        el: this.vueElement,
        router,
        render: h => h(App)
    });

    $('#main').hide();
};
