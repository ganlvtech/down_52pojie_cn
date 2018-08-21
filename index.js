var filesData = [];

var readableSize = function (bytes) {
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

var formatData = function (data) {
    var result = [];
    _.forEach(data, function (item) {
        result.push({
            name: item.name,
            href: item.href,
            url: item.url,
            fullUrl: 'https://down.52pojie.cn' + item.url,
            size: item.size,
            sizeReadable: readableSize(item.size),
            time: moment(item.time).unix(),
            timeFormatted: moment(item.time).format('lll'),
            timeFromNow: moment(item.time).fromNow()
        });
    });
    return result;
};

var renderTbody = (function () {
    var compiled = _.template(document.getElementById('main-tbody-template').innerHTML);
    return function (data) {
        return compiled({'data': formatData(data)});
    };
})();

var setTbody = function (data) {
    document.getElementById('main-tbody').innerHTML = renderTbody(data);
};

var mainSearchInput = document.getElementById('main-search-input');
mainSearchInput.addEventListener('input', _.debounce(function () {
    var regex = new RegExp(mainSearchInput.value, 'i');
    var data = _.filter(filesData, function (o) {
        return regex.test(o.name) || regex.test(o.url);
    });
    setTbody(data);
}, 250));

var tablesort = new Tablesort(document.getElementById('main-table'), {
    descending: true
});

axios.get('list.json')
    .then(function (response) {
        filesData = response.data;
        setTbody(filesData);
        tablesort.refresh();
    })
    .catch(function (error) {
        alert('读取数据错误');
        console.log(error);
    });