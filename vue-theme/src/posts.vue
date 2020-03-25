<style>

</style>

<template>
    <Post v-for="post in posts" :post="post"></Post>
</template>

<script>
    export default {
        ready() {
            this.getPosts();
            window.scrollTo(0,0)
        },

        data() {
            return {
                posts: []
            }
        },

        methods: {
            getPosts() {
                this.$http.get(wp.root + 'wp/v2/posts?_embed').then(function(response) {
                    this.posts = response.data;
                    this.$dispatch('page-title', '');
                }, function(response) {
                    console.log(response);
                });
            }
        }
    }
</script>