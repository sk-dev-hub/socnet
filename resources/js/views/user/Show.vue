<template>
    <div class="w-96 mx-auto">
        <div v-if="posts" class="">
            <h1 class="text-xl mb-4 pb-4 border-b border-gray-300  text-slate-800">Посты:</h1>
            <Post v-for="post in posts" :post="post"></Post>
        </div>
    </div>
</template>

<script>
import Post from '../../components/Post.vue'

export default {
    name: "Show",

    components: {
        Post,
    },

    data() {
        return {
            posts: [],
            userId: this.$route.params.id,
        }
    },

    mounted() {
        this.getPosts()
    },

    methods: {

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
