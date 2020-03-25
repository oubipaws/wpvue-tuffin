<style>

</style>

<template>
    <div class="flex items-center justify-center bg-white rounded-small mt-10 mb-10 shadow-xl">
        <div class="w-full">
            <a v-if="post._embedded['wp:featuredmedia'][0].media_details.sizes['full']" v-link="{ path: base_path + post.slug }">
                <img class="w-full" :src="post._embedded['wp:featuredmedia'][0].media_details.sizes['full'].source_url" />
            </a>

            <div class="entry-box p-32 pb-18 pt-12">
                <div class="post-title text-center text-white pb-4">
                    <h1 class="entry-title single font-sans font-thin text-black text-4xl" v-if="isSingle">{{ post.title.rendered }}</h1>
                    <h2 class="entry-title full font-sans font-thin text-black text-4xl" v-else><a v-link="{ path: base_path + post.slug }">{{ post.title.rendered }}</a></h2>
                </div>

                <div class="entry-content" v-if="isSingle">
                    {{{ post.content.rendered }}}
                </div>
            
                <div class="entry-content" v-else>
                    {{{ post.excerpt.rendered }}}
                </div>

                <div class="flex items-center justify-center">
                    <a v-link="{ path: base_path + post.slug }" class="bg-indigo-800 text-white font-bold rounded-lg border shadow-lg p-4 mt-10" v-if="!isSingle">
		                Read More
	                </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            post: {
                type: Object,
                default() {
                    return {
                        id: 0,
                        slug: '',
                        title: { rendered: '' },
                        content: { rendered: '' }
                    }
                }
            }
        },

        ready() {
            if (!this.post.id) {
                this.getPost();
                this.isSingle = true;
                
                window.scrollTo(0,0)
            }
        },

        data() {
            return {
                base_path: wp.base_path,
                isSingle: false,
            }
        },

        methods: {
            getPost() {
                this.$http.get(wp.root + 'wp/v2/posts/' + this.$route.postId + '?_embed').then(function(response) {
                    this.post = response.data;
                    this.$dispatch('page-title', this.post.title.rendered);
                }, function(response) {
                    console.log(response);
                });
            }
        }
    }
</script>