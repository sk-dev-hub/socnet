<template>
    <div class="flex justify-center mt-3">
        <router-link v-if="token" :to="{name: 'user.personal'}" class="mr-3">Personal</router-link>
        <router-link v-if="!token" :to="{name: 'user.login'}" class="mr-3">Login</router-link>
        <router-link v-if="!token" :to="{name: 'user.registration'}" class="mr-3">Registration</router-link>
        <a href="#" v-if="token" @click.prevent="logout">Logout</a>
    </div>
    <div>
        <router-view  class="flex justify-center mt-5"></router-view>
    </div>
</template>

<script>
export default {
  name: "App",

data(){
        return {
            token: null,
        }
    },

    mounted() {
        this.getToken()
    },

    watch: {
      $route(to, from) {
        this.getToken()
      }
    },

    methods: {
        logout(){
            axios.post('/logout')
            .then( res => {
                localStorage.removeItem('x_xsrf_token')
                this.$router.push({ name: 'user.login' })
            })
        },

        getToken() {
            this.token = localStorage.getItem('x_xsrf_token')
        }
    },



};
</script>
