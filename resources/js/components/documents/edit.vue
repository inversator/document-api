<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ item ? 'Document editing' : 'Document creating'}}</div>
                    <div class="card-body">
                        <router-link to="/" class="btn btn-success"><- back to list</router-link>
                        <hr>
                        <form id="form" @submit.prevent="saveItem($event)">
                            <input v-if="item" name="_method" type="hidden" value="PUT">
                            <div class="form-group">
                                <div v-if="errors.title">
                                    <span class="text-danger" v-for="error in errors.title">{{error}}</span>
                                </div>
                                <label for="title">Document's title</label>
                                <input v-model="document.title" class="form-control" id="title" name="title"/>
                            </div>

                            <div class="form-group">
                                <div v-if="errors.description">
                                    <span class="text-danger" v-for="error in errors.description">{{error}}</span>
                                </div>
                                <label for="description">Description</label>
                                <textarea v-model="document.description" class="form-control" id="description" name="description"></textarea>
                            </div>

                            <div class="form-group">
                                <div v-if="errors.file">
                                    <span class="text-danger" v-for="error in errors.file">{{error}}</span>
                                </div>
                                <label for="file">File</label>

                                <img v-if="item && item.file_link" :src="item.file_link"/>
                                <input class="form-control-file" id="file" name="file" type="file"/>
                            </div>

                            <button type="submit" class="btn btn-primary float-right">{{item ? 'Update' : 'Create'}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            item: Object
        },
        data() {
            return {
                document: {
                    title: '',
                    description: '',
                },
                errors: {},
            }
        },
        mounted() {
            if (this.item)
                this.document = this.item;
        },
        methods: {
            saveItem() {
                let formData = new FormData(document.getElementById('form'));

                if (!this.item) {
                    axios
                        .post('/api/documents', formData)
                        .then(response => {
                            this.$router.push({name: 'Documents', params: {message: 'Document has been added'}});
                        }).catch(error => {
                        if (error.response && error.response.status == 422) {
                            this.errors = error.response.data.errors;
                        }
                    });
                } else {
                    axios
                        .post('/api/documents/' + this.item.id, formData)
                        .then(response => {

                            if (response.data.res)
                                this.$router.push({name: 'Documents', params: {message: 'Document has been updated'}});
                        }).catch(error => {
                        if (error.response && error.response.status == 422) {
                            this.errors = error.response.data.errors;
                        }
                    });
                }
            },
        }
    }
</script>
<style lang="scss" scoped>
    img {
        margin: 0 auto;
        display: block;
        padding: 15px 0;
        border-top: 1px solid #eee;
        margin-top: 20px;
        max-width: 350px;
    }
</style>
