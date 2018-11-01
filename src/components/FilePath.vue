<template>
    <div>
        <ol class="breadcrumb" ref="ol">
            <li class="breadcrumb-item">
                <router-link to="/">{{ baseUrl }}</router-link>
            </li>
            <li v-for="part in parts" :key="part.i" :class="{active: part.active}" class="breadcrumb-item">
                <span v-if="part.active" :title="part.file.description" data-tooltip="toggle">{{ part.file.name }}</span>
                <router-link v-else :to="part.file.path" :title="part.file.description" data-tooltip="toggle">{{ part.file.name }}</router-link>
            </li>
            <li v-if="isSearch" class="breadcrumb-item">
                <span>搜索文件：{{ query }}</span>
            </li>
        </ol>
    </div>
</template>

<script>
    import jQuery from 'jquery';

    const $ = jQuery;
    const _ = require('lodash');

    export default {
        components: {},
        props: {
            baseUrl: {
                type: String,
                default: ''
            },
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
                            file: file,
                            active: false
                        });
                        file = file.parent;
                    } else {
                        file = null;
                    }
                }
                if (parts.length > 0) {
                    if (!this.isSearch) {
                        parts[0].active = true;
                    }
                }
                return _.reverse(parts);
            }
        },
        mounted() {
            this.addTooltipEventListener();
        },
        updated() {
            this.addTooltipEventListener();
        },
        methods: {
            addTooltipEventListener() {
                this.$nextTick(() => {
                    $('.tooltip').remove();
                    $(this.$el).find('[data-tooltip="toggle"]').tooltip({
                        placement: 'bottom',
                        container: 'body',
                        boundary: 'window'
                    });
                });
            }
        }
    };
</script>
