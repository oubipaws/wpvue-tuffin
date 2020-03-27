<style>

</style>

<template>
    <div>
        <div class="text-center text-white font-light pb-10" v-if="isLoading">
            Loading...
        </div>

        <Post v-for="post in posts" :post="post" :key="post.id"></Post>

        <div class="inline-flex flex w-full center-center">
            <div class="content-center w-full text-center">
                <button class="bg-gray-900 hover:bg-gray-400 text-white font-bold py-2 px-4 rounded-l" v-on:click="previousClick()" v-if="currentPage > 1">
                    Prev
                </button>
                <button class="bg-gray-900 hover:bg-gray-400 text-white font-bold py-2 px-4 rounded-r" v-on:click="nextClick()" v-if="currentPage < total_pages">
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
                isLoading: true,
                previousPage: 0,
                nextPage: 0,
                currentPage: 1,
                total_pages: '',
            }
        },

        methods: {
            getPosts() {
                posts_per_page = 3
                if(typeof process.env.VUE_APP_POSTS_PER_PAGE !== 'undefined'){
                    posts_per_page = process.env.VUE_APP_POSTS_PER_PAGE
                }

                let postsUrl = wp.root + 'wp/v2/posts?_embed&per_page=' + posts_per_page
                if (this.currentPage != 1) {
                    postsUrl += '&page=' + this.currentPage
                }

                this.$http.get(postsUrl).then(function(response) {
                    this.total_pages = response.headers('x-wp-totalpages')

                    this.posts = response.data;
                    this.$dispatch('page-title', '');
                    this.isLoading = false;
                }, function(response) {
                    console.log(response);
                });
            },

            previousClick () {
                if (this.currentPage > 1) {
                    this.currentPage = this.currentPage - 1
                }

                this.getPosts()
                window.scrollTo(0, 0)
            },

            nextClick () {
                this.currentPage = this.currentPage + 1

                this.getPosts()
                window.scrollTo(0, 0)
            }
        }
    }
</script>