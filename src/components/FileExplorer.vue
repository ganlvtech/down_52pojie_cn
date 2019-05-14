<template>
    <div>
        <FilePath :base-url="baseUrl" :file="currentFile" :is-search="isSearch" :query="searchQuery"/>
        <SearchBox :query="searchQuery" @submit="submit"/>
        <FileTable :files="files" :desc="false" order-by="name"/>
    </div>
</template>

<script>
    import FilePath from '../components/FilePath.vue';
    import SearchBox from '../components/SearchBox.vue';
    import FileTable from '../components/FileTable.vue';
    import {flattenFiles, fuzzySearchQuery} from '../helpers';
    import toastr from 'toastr';

    export default {
        components: {
            FilePath,
            SearchBox,
            FileTable
        },
        props: {
            baseUrl: {
                type: String,
                default: ''
            },
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
                return this.$route.path;
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
                    let query = this.searchQuery;
                    let regex = new RegExp(query, 'i');
                    result = _.filter(this.all, o => (regex.test(o.name) || regex.test(o.description)));
                    if (result.length <= 0) {
                        let fuzzyQuery = fuzzySearchQuery(query);
                        if (fuzzyQuery !== query) {
                            regex = new RegExp(fuzzyQuery, 'i');
                            result = _.filter(this.all, o => (regex.test(o.name) || regex.test(o.description)));
                        }
                    }
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
            toastr.success(`文件列表加载完成。最后更新于 ${this.stats.lastUpdate}，包含 ${this.stats.fileCount} 个文件、${this.stats.dirCount} 个文件夹，共计 ${this.stats.size}。`);
        },
        methods: {
            submit(query) {
                this.$router.push({name: 'home', query: {query: query}});
            },
        }
    };
</script>
