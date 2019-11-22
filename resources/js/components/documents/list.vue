<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Documents list:</div>
                    <div class="card-body">
                        <div class="notice" v-text="notice"></div>
                        <router-link to="/create" class="btn btn-success">ADD +</router-link>
                        <hr>
                        <div v-if="loading">Loading...</div>

                        <div v-else v-for="document in items" class="item">
                            <list-item :item="document"></list-item>
                        </div>

                        <span v-if="!items.length">No documents</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ListItem from '../modules/list-item'

    export default {
        components: {ListItem},
        props: ['message'],
        data() {
            return {
                items: {},
                loading: false,
                notice: this.message
            }
        },
        mounted() {
            this.getData();
            setTimeout(() => {
                this.zeroDown('notice');
            }, 3000);
        },
        watch: {
            notice() {
                setTimeout(() => {
                    this.zeroDown('notice');
                }, 3000);
            }
        },
        methods: {
            getData() {
                axios
                    .get('/api/documents')
                    .then(response => {
                        this.items = response.data;
                    })
                    .finally(() => (this.loading = false));
            },
            zeroDown(name) {
                this[name] = '';
            }
        }
    }
</script>
<style scoped>
    .notice {
        text-align: center;
        padding: 5px;
        color: #1d68a7;
    }

    .item {
        padding: 10px;
        border-bottom: 1px solid #efefef;
    }
</style>