<template>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <router-link to="/">{{ process.env.VUE_APP_DOWNLOAD_BASE_URL }}</router-link>
            </li>
            <li v-for="part in parts" :key="part.i" :class="{active: part.active}" class="breadcrumb-item">
                <router-link :to="part.file.path">{{ part.file.name }}</router-link>
            </li>
            <li v-if="isSearch" class="breadcrumb-item">
                <span>搜索文件：{{ query }}</span>
            </li>
        </ol>
    </div>
</template>

<script>
    const _ = require('lodash');

    export default {
        components: {},
        props: {
            file: {
                type: Object,
                default() {
                    return {
                        name: '/',
                        path: '/',
                        children: []
                    };
                }
            },
            isSearch: {
                type: Boolean,
                default: false
            },
            query: {
                type: String,
                default: ''
            }
        },
        computed: {
            parts() {
                let parts = [];
                let file = this.file;
                let i = 0;
                while (file) {
                    if (++i > 20) {
                        throw new Error(`Loop too much: ${i}`);
                    }
                    if (file.hasOwnProperty('parent')) {
                        parts.push({
                            key: i,
                            file: file
                        });
                        file = file.parent;
                    } else {
                        file = null;
                    }
                }
                return _.reverse(parts);
            }
        }
    };
</script>
