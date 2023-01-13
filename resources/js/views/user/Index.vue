<template>
    <div class="w-96 mx-auto">
        <div v-if="users">
            <div v-for="user in users" class="flex justify-between items-center mb-6 pb-6 border-b border-gray-300  text-slate-800">
                <router-link :to="{ name: 'user.show', params: {id: user.id} }">
                    <p>{{ user.id }}</p>
                    <p>{{ user.name }}</p>
                    <p>{{ user.email }}</p>
                </router-link> 
                <div>
                    <a @click.prevent="toggleFollowing(user)" :class="['mt-3 inline-flex items-center rounded-md px-4 py-2 text-sm font-medium text-white shadow-sm  focus:outline-none focus:ring-2 focus:ring-offset-2 border border-transparent', user.is_followed ? 'bg-stone-600 hover:bg-stone-700  focus:ring-stone-500' : 'bg-sky-600 hover:bg-sky-700  focus:ring-sky-500' ]"  href="#">
                    {{ user.is_followed ? 'Отписка' : 'Подписка' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Index",

    data() {
        return {
            users: [],
        }
    },

    mounted() {
        this.getUsers()
    },

    methods: {
        getUsers(){
            axios.get('/api/users')
            .then( res => {
                this.users = res.data.data
            })
        },

        toggleFollowing(user){
            axios.post(`/api/users/${user.id}/toggle_following`)
            .then( res => {
                user.is_followed = res.data.is_followed
            })
        },
    }
}
</script>

<style scoped>

</style>
