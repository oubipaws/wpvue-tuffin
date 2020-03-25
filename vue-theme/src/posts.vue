<style>

</style>

<template>
    <div>
        <Post v-for="post in posts" :post="post"></Post>

        <div class="inline-flex flex w-full center-center">
            <div class="content-center w-full text-center">
                <button class="bg-gray-900 hover:bg-gray-400 text-white font-bold py-2 px-4 rounded-l">
                    Prev
                </button>
                <button class="bg-gray-900 hover:bg-gray-400 text-white font-bold py-2 px-4 rounded-r">
                    Next
                </button>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        ready() {
            this.getPosts();
            window.scrollTo(0,0)
        },

        created() {
            if (this.$route.query.page > 1) {
                this.currentPage = parseInt(this.$route.query.page)
            }            
        },

        data() {
            return {
                posts: [],
                currentPage: 1
            }
        },

        methods: {
            getPosts() {
                let postsUrl = wp.root + 'wp/v2/posts?_embed'

                if (this.$route.query.page !== undefined) {
                    postsUrl += '?page=' + this.$route.query.page
                }

                this.$http.get(postsUrl).then(function(response) {
                    this.posts = response.data;
                    this.$dispatch('page-title', '');
                }, function(response) {
                    console.log(response);
                });
            }
        }
    }
</script>