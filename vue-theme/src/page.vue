<style>

</style>

<template>
    <div class="flex items-center justify-center bg-white rounded-small mt-10 mb-10 shadow-xl">
        <div class="w-full">
            <div class="entry-box p-32 pb-18 pt-12">
                <div class="post-title text-center text-white pb-4">
                    <h1 class="entry-title single font-sans font-thin text-black text-4xl">{{ page.title.rendered }}</h1>
                </div>
                
                <div class="entry-content" v-html="page.content.rendered">
                    {{ page.content.rendered }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        ready() {
            this.getPage();
        },

        data() {
            return {
                page: {
                    id: 0,
                    slug: '',
                    title: { rendered: '' },
                    content: { rendered: '' }
                }
            }
        },

        methods: {
            getPage() {
                this.$http.get(wp.root + 'wp/v2/pages/' + this.$route.postId).then(function(response) {
                    this.page = response.data;
                    this.$dispatch('page-title', this.page.title.rendered);
                }, function(response) {
                    console.log(response);
                });
            }
        },

        route: {
            canReuse() {
                return false;
            }
        }
    }
</script>