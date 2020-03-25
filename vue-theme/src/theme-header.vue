<style>
    .header {
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        margin-bottom: 50px;
    }
    .header .container { display: flex; }
    .header .site-title { width: 50%; }
    .header .nav {
        list-style: none;
        margin: 0;
        padding: 0;
        width: 50%;
        text-align: right;
    }
    .header .nav li {
        display: inline;
        margin-left: 15px;
    }
    .header .v-link-active { color: #333; }
</style>

<template>
    <nav class="bg-gray-900 p-2 mt-0 fixed w-full z-10 top-0">
        <div class="container mx-auto flex flex-wrap items-center">
            
            <div class="flex w-full md:w-1/2 justify-center md:justify-start text-white font-extrabold">
                <a class="text-white no-underline hover:text-white hover:no-underline" v-link="{ path: base_path }">
                    <span class="text-2xl pl-2"><i class="em em-grinning"></i> {{ site_name }}</span>
                </a>
            </div>
			
            <div class="flex w-full pt-2 content-center justify-between md:w-1/2 md:justify-end">
                <ul class="list-reset flex justify-between flex-1 md:flex-none items-center">
                    <li v-for="page in pages" class="mr-3">
					    <a v-link="{ path: base_path + page.slug }" class="inline-block py-2 px-4 text-white no-underline font-bold">{{ page.title.rendered }}</a>
				    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        ready() {
            this.getPages();
        },

        data() {
            return {
                base_path: wp.base_path,
                site_name: wp.site_name,
                pages: []
            }
        },

        methods: {
            getPages() {
                this.$http.get(wp.root + 'wp/v2/pages').then(function(response) {
                    this.pages = response.data;
                }, function(response) {
                    console.log(response);
                });
            }
        }
    }
</script>