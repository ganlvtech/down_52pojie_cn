<template>
    <div>
        <FilePath :file="currentFile" :is-search="isSearch" :query="searchQuery"/>
        <form class="form-inline" action="/" method="post" @submit.prevent="submitForm">
            <div class="form-group mb-2">
                <label for="search-input">查找文件：</label>
            </div>
            <div class="form-group mr-2 mb-2">
                <input id="search-input" ref="searchInput" :value="searchQuery" type="search" name="query" class="form-control" @input="submitDebounce">
            </div>
            <button type="submit" class="btn btn-primary mr-2 mb-2">搜索</button>
            <a class="btn btn-outline-primary mb-2" @click="submitAll">全部文件</a></form>
        <FileTable :files="files" :desc="false" order-by="name"/>
    </div>
</template>

<script>
    import FilePath from '../components/FilePath.vue';
    import FileTable from '../components/FileTable.vue';
    import {flattenFiles} from '../helpers';
    import toastr from 'toastr';

    export default {
        components: {
            FilePath,
            FileTable
        },
        props: {
            root: {
                type: Object,
                default() {
                    return {
                        name: '/',
                        path: '/',
                        size: 0,
                        time: 0,
                        isDir: true,
                        children: []
                    };
                }
            }
        },
        data() {
            return {
                currentFiles: []
            };
        },
        computed: {
            path() {
                return decodeURI(this.$route.path);
            },
            stats() {
                let lastUpdate = this.root.timeFromNowForHuman;
                let count = this.all.length;
                let countByType = _.countBy(this.all, 'isDir');
                let size = this.root.sizeReadable;
                return {
                    lastUpdate: lastUpdate,
                    count: count,
                    dirCount: countByType.true,
                    fileCount: countByType.false,
                    size: size
                };
            },
            all() {
                return flattenFiles(this.root);
            },
            indices() {
                return _.keyBy(this.all, 'path');
            },
            currentFile() {
                const path = this.path;
                if (this.indices.hasOwnProperty(path)) {
                    return this.indices[path];
                } else {
                    const pathAppend = `${path}/`;
                    if (this.indices.hasOwnProperty(pathAppend)) {
                        return this.indices[pathAppend];
                    }
                }
                toastr.error(`文件未找到：${path}`);
                return null;
            },
            isSearch() {
                return this.$route.query.hasOwnProperty('query');
            },
            searchQuery() {
                if (this.isSearch) {
                    return this.$route.query.query;
                } else {
                    return '';
                }
            },
            files() {
                let result = [];
                if (this.isSearch) {
                    const regex = new RegExp(this.searchQuery, 'i');
                    result = _.filter(this.all, o => regex.test(o.name));
                } else {
                    const currentFile = this.currentFile;
                    if (currentFile) {
                        result = currentFile.children;
                    }
                }
                return result;
            }
        },
        mounted() {
            toastr.success(`文件列表加载完成。最后更新于 ${this.stats.lastUpdate}，爱盘包含 ${this.stats.fileCount} 个文件、${this.stats.dirCount} 个文件夹，共计 ${this.stats.size}。`);
        },
        methods: {
            submit(query) {
                this.$router.push({name: 'home', query: {query: query}});
            },
            submitDebounce: _.debounce(function () {
                this.submit(this.$refs.searchInput.value);
            }, 500),
            submitForm() {
                this.submit(this.$refs.searchInput.value);
            },
            submitAll() {
                this.submit('');
            }
        }
    };
</script>
