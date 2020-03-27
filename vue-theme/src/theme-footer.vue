<template>
    <div>
        <footer class="footer text-center pb-20 mt-8">
            <div class="social-icons text-center text-white text-xl" v-if="!optionsLoading">
                <a title="Twitter" href="https://twitter.com/{{ tuffin_options.social.tuffin_social_twitter }}" v-if="tuffin_options.social.tuffin_social_twitter" class="h-24 w-24 pr-5">
                    <i class="fa fa-twitter"><span class="sr-only">Twitter</span></i>
                </a>
                <a title="Facebook" href="https://www.facebook.com/{{ tuffin_options.social.tuffin_social_facebook }}" v-if="tuffin_options.social.tuffin_social_facebook" class="h-24 w-24 pr-5">
                    <i class="fa fa-facebook"><span class="sr-only">Facebook</span></i>
                </a>
                <a title="LinkedIn" href="https://www.linkedin.com/in/{{ tuffin_options.social.tuffin_social_linkedin }}/" v-if="tuffin_options.social.tuffin_social_linkedin" class="h-24 w-24 pr-5">
                    <i class="fa fa-linkedin"><span class="sr-only">LinkedIn</span></i>
                </a>
                <a title="CodePen" href="https://codepen.io/{{ tuffin_options.social.tuffin_social_codepen }}" v-if="tuffin_options.social.tuffin_social_codepen" class="h-24 w-24 pr-5">
                    <i class="fa fa-codepen"><span class="sr-only">CodePen</span></i>
                </a>
                <a title="Dribbble" href="https://dribbble.com/{{ tuffin_options.social.tuffin_social_dribbble }}" v-if="tuffin_options.social.tuffin_social_dribbble" class="h-24 w-24 pr-5">
                    <i class="fa fa-dribbble"><span class="sr-only">Dribble</span></i>
                </a>
                <a title="Instagram" href="https://www.instagram.com/{{ tuffin_options.social.tuffin_social_instagram }}/" v-if="tuffin_options.social.tuffin_social_instagram" class="h-24 w-24">
                    <i class="fa fa-instagram"><span class="sr-only">Instagram</span></i>
                </a>
            </div>
            <div class="copyright-text text-white text-sm mb-3 mt-3 text-gray-500">
                &copy; {{ new Date().getFullYear() }} - <a title="{{ site_name }}" v-link="{ path: base_path }">{{ site_name }}</a>
            </div>

            <div class="inline-flex flex w-full center-center">
                <div class="content-center w-full text-center">
                    <button class="back-to-top w-8 h-8 bg-white text-gray-800 font-extrabold" v-on:click="scrollToTop()" title="Scroll To Top">
                        ^
                        <div class="sr-only">Scroll To Top</div>
                    </button>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
    export default {
        ready() {
            this.getOptions();
        },

        data() {
            return {
                base_path: wp.base_path,
                site_name: wp.site_name,
                optionsLoading: true,
                tuffin_options: []
            }
        },

        methods: {
            getOptions() {
                this.$http.get(wp.root + 'tuffin/v1/options').then(function(response) {
                    this.tuffin_options = response.data;
                    this.optionsLoading = false;
                }, function(response) {
                    console.log(response);
                });
            },

            scrollToTop() {
                window.scrollTo(0,0)
            }
        }
    }    
</script>