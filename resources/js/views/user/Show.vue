<template>
    <div class="w-96 mx-auto">
        <Stat :stats="stats"/>
        <div v-if="posts" class="">
            <h1 class="text-xl mb-4 pb-4 border-b border-gray-300  text-slate-800">Посты:</h1>
            <Post v-for="post in posts" :post="post"></Post>
        </div>
    </div>
</template>

<script>
import Post from '../../components/Post.vue'
import Stat from '../../components/Stat.vue'


export default {
    name: "Show",

    components: {
        Post,
        Stat,
    },

    data() {
        return {
            posts: [],
            userId: this.$route.params.id,
            stats: [],
        }
    },

    mounted() {
        this.getPosts()
        this.getStats()

    },

    methods: {

        getStats(){
            axios.post('/api/users/stats', {user_id: this.userId})
            .then( res => {
                this.stats = res.data.data 
                console.log(res.data.data)
            })
        },

        getPosts(){
            axios.get(`/api/users/${this.userId}/posts`)
            .then( res => {
                this.posts = res.data.data
            })
        },

        
    },


}
</script>

<style scoped>

</style>
