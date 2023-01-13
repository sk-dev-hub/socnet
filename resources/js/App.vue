<template>
    <div>
        <div class="flex justify-between p-8 w-96 mx-auto">

            <router-link v-if="!token" :to="{ name: 'user.login'}">Логин</router-link>
            <router-link v-if="token" :to="{ name: 'user.index'}">Юзеры</router-link>
            <router-link v-if="token" :to="{ name: 'user.feed'}">Лента</router-link>
            <router-link v-if="token" :to="{ name: 'user.personal'}">Моя страница</router-link>
            <router-link v-if="!token" :to="{ name: 'user.registration'}">Регистрация</router-link>
            <a v-if="token" @click.prevent="logout" href="#">Выход</a>
        </div>

        <router-view></router-view>
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
