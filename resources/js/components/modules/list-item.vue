<template>
    <div>
        <div v-if="deleted">Document removed</div>
        <div v-else>
            <span class="title" title="edit document" @click="editItem(item)">{{item.title}}</span><span class="date">added: {{item.created_at}}</span>
            <div class="control">
                <span class="delete" title="delete" @click="delItem(item)">x</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "list-item",
        props: {
            item: Object
        },
        data() {
            return {
                deleted: false
            }
        },
        watch: {
            deleted() {
            }
        },
        methods: {
            delItem(item) {

                this.$swal({
                    title: 'Remove it?',
                    text: item.title + ' will be removed',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes remove!'
                }).then(result => {
                    if (result) {
                        axios
                            .delete('/api/documents/' + item.id)
                            .then(response => {
                                this.deleted = true
                            }).catch(error => {
                            console.error(error)
                        });
                    }
                }, (error) => {
                    console.error(error)
                });
            },

            editItem(item){
                this.$router.push({name: 'editDocument', params: {item: item, id: item.id}});
            }
        }
    }
</script>

<style lang="scss" scoped>
    .date {
        color: #a0a7a1;
        padding: 10px;
    }

    .title {
        font-size: 14pt;
        cursor: pointer;
        color: #1d68a7;
    }

    .control {
        font-size: large;
        float: right;
        .delete {
            cursor: pointer;
            color: red;
        }
    }
</style>