<template>
    <div class="table-responsive">
        <table class="table table-sm table-hover sortable table-bordered">
            <thead class="thead-light">
                <tr>
                    <th class="text-left icon" @click="sort('isDir')"/>
                    <th class="text-left name" @click="sort('name')">文件名</th>
                    <th class="text-right size" @click="sort('size')">文件大小</th>
                    <th class="text-left date" data-sort-default @click="sort('time')">上传日期</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="file in sortedFiles" :key="file.path">
                    <td class="icon">
                        <svg v-if="file.isDir" version="1.1" width="14" height="16" aria-hidden="true">
                            <path fill-rule="evenodd" d="M13 4H7V3c0-.66-.31-1-1-1H1c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1V5c0-.55-.45-1-1-1zM6 4H1V3h5v1z"/>
                        </svg>
                        <svg v-else version="1.1" width="12" height="16" aria-hidden="true">
                            <path fill-rule="evenodd" d="M6 5H2V4h4v1zM2 8h7V7H2v1zm0 2h7V9H2v1zm0 2h7v-1H2v1zm10-7.5V14c0 .55-.45 1-1 1H1c-.55 0-1-.45-1-1V2c0-.55.45-1 1-1h7.5L12 4.5zM11 5L8 2H1v12h10V5z"/>
                        </svg>
                    </td>
                    <td class="text-left name">
                        <router-link v-if="file.isDir" :to="file.path" :title="file.description" data-tooltip="toggle">{{ file.name }}</router-link>
                        <a v-else :href="file.fullUrl" :title="file.description" target="_blank" data-tooltip="toggle">{{ file.name }}</a>
                    </td>
                    <td :title="file.size" class="text-right size">{{ file.sizeReadable }}</td>
                    <td :title="file.timeForHuman" class="text-left date">{{ file.timeFromNowForHuman }}</td>
                </tr>
                <tr v-if="empty">
                    <td class="icon"></td>
                    <td class="name">没有文件</td>
                    <td class="size">-</td>
                    <td class="time">-</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import jQuery from 'jquery';

    const $ = jQuery;

    export default {
        props: {
            files: {
                type: Array,
                default() {
                    return [];
                }
            },
            orderBy: {
                type: String,
                default: 'name'
            },
            desc: {
                type: Boolean,
                default: false
            }
        },
        computed: {
            sortedFiles() {
                const order = this.desc ? 'desc' : 'asc';
                return _.orderBy(this.files, [this.orderBy], [order]);
            },
            defaultIsDesc() {
                return {
                    isDir: true,
                    name: false,
                    size: false,
                    time: true
                };
            },
            empty() {
                return this.files.length <= 0;
            }
        },
        mounted() {
            this.addTooltipEventListener();
        },
        updated() {
            this.addTooltipEventListener();
        },
        methods: {
            sort(field) {
                if (field === this.orderBy) {
                    this.desc = !this.desc;
                } else {
                    this.orderBy = field;
                    this.desc = this.defaultIsDesc[field];
                }
            },
            addTooltipEventListener() {
                this.$nextTick(() => {
                    $('.tooltip').remove();
                    $(this.$el).find('[data-tooltip="toggle"]').tooltip({
                        placement: 'auto',
                        container: 'body',
                        boundary: 'window'
                    });
                });
            }
        }
    };
</script>

<style scoped lang="scss">
    .icon {
        width: 0.8rem;
        color: rgba(3, 47, 98, 0.55);
        box-sizing: content-box;

        svg {
            fill: currentColor;
        }
    }

    .name {
        min-width: 10em;
    }

    .size {
        width: 5em;
    }

    .date {
        width: 5em;
    }

    .size, .date {
        white-space: nowrap;
    }
</style>
