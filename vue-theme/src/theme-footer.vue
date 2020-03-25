<style>
    .footer {
        font-size: 14px;
        color: #999;
        margin-top: 50px;
    }
</style>

<template>
    <footer class="footer text-center pb-20">
        <div class="social-icons text-center text-white text-xl">
            <a href="https://twitter.com/{{ tuffin_options.social.tuffin_social_twitter }}" v-if="tuffin_options.social.tuffin_social_twitter" class="h-24 w-24 pr-5"><i class="fa fa-twitter"><span class="sr-only">Twitter</span></i></a>
            <a href="https://www.facebook.com/{{ tuffin_options.social.tuffin_social_facebook }}" v-if="tuffin_options.social.tuffin_social_facebook" class="h-24 w-24 pr-5"><i class="fa fa-facebook"><span class="sr-only">Facebook</span></i></a>
            <a href="https://www.linkedin.com/in/{{ tuffin_options.social.tuffin_social_linkedin }}/" v-if="tuffin_options.social.tuffin_social_linkedin" class="h-24 w-24 pr-5"><i class="fa fa-linkedin"><span class="sr-only">LinkedIn</span></i></a>
            <a href="https://codepen.io/{{ tuffin_options.social.tuffin_social_codepen }}" v-if="tuffin_options.social.tuffin_social_codepen" class="h-24 w-24 pr-5"><i class="fa fa-codepen"><span class="sr-only">CodePen</span></i></a>
            <a href="https://dribbble.com/{{ tuffin_options.social.tuffin_social_dribbble }}" v-if="tuffin_options.social.tuffin_social_dribbble" class="h-24 w-24 pr-5"><i class="fa fa-dribbble"><span class="sr-only">Dribble</span></i></a>
            <a href="https://www.instagram.com/{{ tuffin_options.social.tuffin_social_instagram }}/" v-if="tuffin_options.social.tuffin_social_instagram" class="h-24 w-24"><i class="fa fa-instagram"><span class="sr-only">Instagram</span></i></a>
        </div>
        <div class="copyright-text text-white text-sm mb-3 mt-3 text-gray-500">
            &copy; {{ new Date().getFullYear() }} - <a v-link="{ path: base_path }">{{ site_name }}</a>
        </div>

        <div class="back-to-top hidden">
            ^
        </div>
    </footer>
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
                tuffin_options: []
            }
        },

        methods: {
            getOptions() {
                this.$http.get(wp.root + 'tuffin/v1/options').then(function(response) {
                    console.table(response.data);
                    this.tuffin_options = response.data;
                }, function(response) {
                    console.log(response);
                });
            }
        }
    }    
</script>