<template>
    <div class="container">
        <FileExplorer v-if="loaded" :root="data"/>
    </div>
</template>

<script>
    import FileExplorer from '../components/FileExplorer';
    import {prepareFiles} from '../helpers';

    const axios = require('axios');
    const toastr = require('toastr');

    export default {
        components: {
            FileExplorer
        },
        data() {
            return {
                data: {},
                path: {},
                loaded: false
            };
        },
        computed: {
            todayTimestamp() {
                return Math.floor(Date.now() / 1000 / 86400) * 86400;
            }
        },
        mounted() {
            toastr.info('爱盘搜索扩展插件加载完成，正在加载文件列表');
            axios.get(`${process.env.BASE_URL}list.json?t=${this.todayTimestamp}`)
                .then(response => {
                    this.data = prepareFiles(response.data);
                    this.path = this.data;
                    this.loaded = true;
                    $('#main').hide();
                })
                .catch(error => {
                    toastr.error(error.message);
                    throw error;
                });
        }
    };
</script>
