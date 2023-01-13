<template>
    <div class="w-96 mx-auto">
        <Stat :stats="stats"/>
        <div class="mb-5">
            <div class="mb-3">
                <input v-model="title" type="text" placeholder="title" class="w-96  p-2 rounded-xl border border-slate-400">
                <div v-if="errors.title">
                    <p v-for="error in errors.title" class="mt-1 text-sm text-rose-600" >{{ error }}</p>
                </div>
            </div>

            <div class="mb-3">
                <textarea v-model="content" placeholder="content" class="w-96 p-2 rounded-xl border border-slate-400"></textarea>
                <div v-if="errors.content">
                    <p v-for="error in errors.content" class="mt-1 text-sm text-rose-600" >{{ error }}</p>
                </div>
            </div>

            <div class="flex mb-3">
                <div>
                    <input @change="storeImage" ref="file" type="file" class="hidden">
                    <a href="#" class="inline-flex items-center rounded-md border border-transparent bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2" @click.prevent="selectFile()">Image</a>
                </div>
                <div v-if="image">
                    <a href="#" @click.prevent="image = null" class="ml-3 inline-flex items-center rounded-md border border-transparent bg-cyan-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2">Cancel</a>
                </div>
            </div>
            <div v-if="image" class="mb-3">
                <img :src="image.url" alt="preview">
            </div>
            <div class="flex mb-3">
                <a @click.prevent="store" href="#" class="ml-auto  items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 ">Publish</a>
            </div>
        </div>
        <div v-if="posts" class="">
            <h1 class="text-xl mb-4 pb-4 border-b border-gray-300  text-slate-800">Посты:</h1>
            <Post v-for="post in posts" :post="post"></Post>
        </div>
    </div>
</template>

<script>
import Post from "../../components/Post.vue"
import Stat from "../../components/Stat.vue"


export default {
    name: "Personal",

    components: {
        Post,
        Stat,
    },

    data() {
        return {
            title: null,
            content: null,
            image: null,
            posts: [],
            errors: [],
            stats: [],
        }
    },

    mounted() {
        this.getPosts()
        this.getStats()
    },

    methods: {

        getStats(){
            axios.post('/api/users/stats', {user_id: null})
            .then( res => {
                this.stats = res.data.data 
            })
        },

        getPosts(){
            axios.get('/api/posts')
            .then( res => {
                this.posts = res.data.data
            })
        },

        store(){
            const id = this.image ? this.image.id : null

            axios.post('/api/posts',{
                title: this.title,
                content: this.content,
                image_id: id
            })
            .then( res => {
                this.title = ''
                this.content = ''
                this.image = null
                
                this.posts.unshift(res.data.data)
            })
            .catch( e => {
               this.errors = e.response.data.errors;
            })
        },
        selectFile() {
            this.fileInput = this.$refs.file;
            this.fileInput.click();
        },
        storeImage(e) {
            const file = e.target.files[0]
            const formData = new FormData()
            formData.append('image', file)
            
            axios.post('/api/post_images', formData)
            .then( res => {
                this.image = res.data.data
            })
        }
    },


}
</script>

<style scoped>

</style>
