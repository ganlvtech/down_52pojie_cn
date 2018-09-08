var FileSystem = function (root) {
    this.root = root;
    this.prepare();
};

FileSystem.isDir = function (item) {
    return item.hasOwnProperty('children');
};

FileSystem.prototype.prepare = function () {
    this.indices = {};
    var _this = this;
    var prepareFunc = function (currentItem, parentPath) {
        var path = currentItem.name === '/' || _.endsWith(parentPath, '/') ? parentPath + currentItem.name : parentPath + '/' + currentItem.name;
        currentItem['path'] = path;
        if (parentPath.length > 0) {
            currentItem['parentPath'] = parentPath;
        }
        _this.indices[path] = currentItem;
        if (FileSystem.isDir(currentItem)) {
            _.forEach(currentItem.children, function (item) {
                prepareFunc(item, path);
            });
        }
    };
    prepareFunc(this.root, '');
};

FileSystem.prototype.get = function (path) {
    if (!_.startsWith(path, '/')) {
        return false;
    }
    if (path === '/') {
        return this.root;
    }
    path = _.trimEnd(path, '/');
    if (!this.indices.hasOwnProperty(path)) {
        return false;
    }
    return this.indices[path];
};

FileSystem.prototype.find = function (name) {
    var regex = new RegExp(name, 'i');
    return _.filter(this.indices, function (o) {
        return regex.test(o.name);
    });
};

var DownloadCenter = function () {
};

DownloadCenter.baseUrl = 'https://down.52pojie.cn';

DownloadCenter.readableSize = function (bytes) {
    if (bytes < 0) {
        return '';
    }
    if (bytes < 1024) {
        return bytes + ' B';
    }
    if (bytes < 1024 * 1024) {
        return Math.round(10 * bytes / 1024) / 10 + ' K';
    }
    if (bytes < 1024 * 1024 * 1024) {
        return Math.round(10 * bytes / (1024 * 1024)) / 10 + ' M';
    }
    return Math.round(10 * bytes / (1024 * 1024 * 1024)) / 10 + ' G';
};

DownloadCenter.prototype.init = function () {
    var _this = this;
    this.tablesort = new Tablesort(document.getElementById('main-table'), {
        descending: true
    });

    _.forEach(document.querySelectorAll('.alert.alert-dismissible button.close'), function (el) {
        el.addEventListener('click', function (e) {
            this.parentElement.style.display = 'none';
        });
    });

    axios.get('list.json')
        .then(function (response) {
            _this.data = response.data;
            _this.fileSystem = new FileSystem(_this.data);
            var root = _this.fileSystem.get('/');
            document.getElementById('file-loading-msg').textContent = '文件列表加载完成。最后更新于 ' + moment.unix(root.time).fromNow() + '，爱盘文件共计 ' + DownloadCenter.readableSize(root.size) + '。';
            document.getElementById('file-loading').style.display = 'block';
            _this.visit('/');
        })
        .catch(function (error) {
            alert('读取数据错误');
            console.log(error);
        });
};

DownloadCenter.prototype.tableFormatData = function (files) {
    return _.map(files, function (file) {
        if (file.name === '..') {
            return {
                name: '..',
                path: file.path,
                fullUrl: DownloadCenter.baseUrl + file.path,
                size: 0,
                sizeReadable: '',
                time: moment().unix(),
                timeFormatted: '',
                timeFromNow: '',
                isDir: false
            };
        }
        return {
            name: file.name,
            path: file.path,
            fullUrl: DownloadCenter.baseUrl + file.path,
            size: file.size,
            sizeReadable: DownloadCenter.readableSize(file.size),
            time: file.time,
            timeFormatted: moment.unix(file.time).format('lll'),
            timeFromNow: moment.unix(file.time).fromNow(),
            isDir: FileSystem.isDir(file)
        };
    });
};

DownloadCenter.prototype.renderTbody = function (data) {
    var compiled = _.template(document.getElementById('main-tbody-template').innerHTML);
    return compiled({'data': this.tableFormatData(data)});
};

DownloadCenter.prototype.setTbody = function (data) {
    document.getElementById('main-tbody').innerHTML = this.renderTbody(data);
    this.tablesort.refresh();
    var _this = this;
    _.forEach(document.getElementById('main-tbody').querySelectorAll('a'), function (anchor) {
        anchor.addEventListener('click', function (e) {
            if (_this.visit(decodeURI(anchor.dataset.path))) {
                e.preventDefault();
            }
            return true;
        });
    });
};

DownloadCenter.prototype.setCurrentPath = function (path) {
    if (!_.startsWith(path, '/')) {
        return false;
    }
    var parts;
    if (path === '/') {
        parts = [];
    } else {
        path = _.trim(path, '/');
        parts = _.split(path, '/');
    }
    var parentPath = '/';
    parts = _.map(parts, function (part) {
        var result = {
            name: part,
            path: parentPath + part,
            active: false
        };
        parentPath = parentPath + part + '/';
        return result;
    });
    if (parts.length > 0) {
        parts[parts.length - 1].active = true;
    }
    parts.unshift({
        name: 'down.52pojie.cn',
        path: '/',
        active: false
    });
    parts = _.map(parts, function (part) {
        part.fullUrl = DownloadCenter.baseUrl + part.path;
        return part;
    });

    var compiled = _.template(document.getElementById('current-path-template').innerHTML);
    var html = compiled({'data': this.tableFormatData(parts)});
    document.getElementById('current-path').innerHTML = html;

    var _this = this;
    _.forEach(document.getElementById('current-path').querySelectorAll('a'), function (anchor) {
        anchor.addEventListener('click', function (e) {
            if (_this.visit(anchor.dataset.path)) {
                e.preventDefault();
            }
            return true;
        });
    });
};

DownloadCenter.prototype.visit = function (path) {
    var item = this.fileSystem.get(path);
    if (!FileSystem.isDir(item)) {
        return false;
    }
    var files = item.children;
    if (item.hasOwnProperty('parentPath')) {
        files = _.concat(files, [{
            name: '..',
            path: item.parentPath,
        }]);
    }
    this.setTbody(files);
    this.setCurrentPath(path);
    return true;
};

DownloadCenter.prototype.search = function (value) {
    this.setTbody(this.fileSystem.find(value));
};

var downloadCenter = new DownloadCenter();
downloadCenter.init();

var mainSearchInput = document.getElementById('main-search-input');
mainSearchInput.addEventListener('input', _.debounce(function () {
    downloadCenter.search(mainSearchInput.value);
}, 250));

document.getElementById('main-search-visit-all').addEventListener('click', function () {
    downloadCenter.search('');
});

document.getElementById('main-search-visit-root').addEventListener('click', function () {
    downloadCenter.visit('/');
});

