<template>
    <div>
        <form class="form-inline" action="/" method="post" @submit.prevent="submitForm">
            <div class="form-group mb-2">
                <label for="search-input">查找文件：</label>
            </div>
            <div class="form-group mr-2 mb-2">
                <input id="search-input" ref="searchInput" :value="query" type="search" name="query" class="form-control" @input="submitDebounce" title="默认使用完全匹配。完全匹配无结果时使用模糊匹配。输入正则表达式可执行高级搜索。">
            </div>
            <button type="submit" class="btn btn-primary mr-2 mb-2">搜索</button>
            <button type="button" class="btn btn-outline-primary mb-2" @click="submitAll">全部文件</button>
        </form>
    </div>
</template>

<script>
    export default {
        props: {
            query: {
                type: String,
                default: ''
            }
        },
        methods: {
            submit(query) {
                this.$emit('submit', query);
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
