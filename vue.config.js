const path = require('path');
const fs = require('fs');

function FancyIndexPlugin(options) {
}

FancyIndexPlugin.prototype.apply = function (compiler) {
    compiler.plugin('emit', function (compilation, callback) {
        for (let filename in compilation.assets) {
            if (filename === 'index.html') {
                const file = compilation.assets[filename];
                const html = file.source();
                const splitted = html.split('</h1>');
                const header = splitted[0];
                const footer = splitted[1];
                compilation.assets[`${process.env.FANCY_INDEX_DIR}/header.html`] = {
                    source: function () {
                        return header;
                    },
                    size: function () {
                        return header.length;
                    }
                };
                compilation.assets[`${process.env.FANCY_INDEX_DIR}/footer.html`] = {
                    source: function () {
                        return footer;
                    },
                    size: function () {
                        return footer.length;
                    }
                };
            }
        }
        callback();
    });
};

module.exports = {
    lintOnSave: process.env.NODE_ENV !== 'production',
    productionSourceMap: false,
    css: {
        extract: true
    },
    baseUrl: process.env.BUILD_GITHUB_PAGES ? '/down_52pojie_cn/' : undefined,
    assetsDir: process.env.USE_FANCY_INDEX ? process.env.FANCY_INDEX_DIR : undefined,
    chainWebpack: config => {
        if (process.env.USE_FANCY_INDEX) {
            config.plugin('fancy-index-plugin').use(FancyIndexPlugin)
        }
        config.optimization.splitChunks({});
    }
};
