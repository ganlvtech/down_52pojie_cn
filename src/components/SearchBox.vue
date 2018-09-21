<template>
    <div>
        <form class="form-inline" action="/" method="post" @submit.prevent="submitForm">
            <div class="form-group mb-2">
                <label for="search-input">查找文件：</label>
            </div>
            <div class="form-group mr-2 mb-2">
                <input id="search-input" ref="searchInput" :value="query" type="search" name="query" class="form-control" @input="submitDebounce">
            </div>
            <button type="submit" class="btn btn-primary mr-2 mb-2">搜索</button>
            <a class="btn btn-outline-primary mb-2" @click="submitAll">全部文件</a>
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
