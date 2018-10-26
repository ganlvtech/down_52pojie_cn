function FancyIndexPlugin(options) {
}

FancyIndexPlugin.prototype.apply = function (compiler) {
    compiler.plugin('emit', function (compilation, callback) {
        for (let filename in compilation.assets) {
            if (filename === 'index.html') {
                const file = compilation.assets[filename];
                const html = file.source();
                const splitted = html.split('/</h1>');
                const header = splitted[0];
                const footer = splitted[1];
                compilation.assets[`${process.env.ASSETS_DIR}/header.html`] = {
                    source: function () {
                        return header;
                    },
                    size: function () {
                        return header.length;
                    }
                };
                compilation.assets[`${process.env.ASSETS_DIR}/footer.html`] = {
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
    baseUrl: process.env.BASE_URL,
    assetsDir: process.env.ASSETS_DIR,
    chainWebpack: config => {
        if (process.env.USE_FANCY_INDEX) {
            config.plugin('fancy-index-plugin').use(FancyIndexPlugin);
        }
        config.optimization.splitChunks({});
    }
};
