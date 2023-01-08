<template>
    <div>
        <div class="mb-8 pb-8 border-b border-gray-300">
            <h1 class="text-xl text-slate-800">{{ post.title }}</h1>
            <img v-if="post.image_url" :src="post.image_url" :alt="post.title" class="my-3 w-full">
            <p class="text-slate-600">{{ post.content }}</p>

            <div v-if="post.reposted_post" class="bg-gray-50 p-4 my-4 border border-gray-100">
                <h1 class="text-xl text-slate-800">{{ post.reposted_post.title }}</h1>
                    <img v-if="post.reposted_post.image_url" :src="post.reposted_post.image_url" :alt="post.reposted_post.title" class="my-3 w-full">
                <p class="text-slate-600">{{ post.reposted_post.content }}</p>
            </div>

            <div class="flex justify-between items-center mt-2">
                <div class="flex">
                    <div class="flex">
                        <svg @click.prevent="toggleLike(post)"  xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" 
                        :class="['mr-2 stroke-sky-500 w-6 h-6 cursor-pointer hover:fill-sky-500 ', post.is_liked ? 'fill-sky-500' : 'fill-white']">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>

                        <p class=" text-slate-600">{{post.likes_count}}</p>
                    </div>
                    <div class="flex ml-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 22 22" stroke-width="1.5" stroke="currentColor" 
                            @click.prevent="openRepost()"
                            class="mr-2 text-slate-600  w-5 h-5 cursor-pointer hover:text-slate-800">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 15l6-6m0 0l-6-6m6 6H9a6 6 0 000 12h3" />
                        </svg>
                        <p class=" text-slate-600">0</p>
                    </div>
                </div>

                <p class="text-right text-sm text-slate-400">{{ post.date }}</p>
            </div>

                    <div v-if="is_repost" class="mt-5">
                        <div>
                            <input v-model="title" type="text" placeholder="title" class="w-96 mb-3 p-2 rounded-xl border border-slate-400">
                        </div>
                        <div>
                            <textarea v-model="content" placeholder="content" class="w-96 mb-3 p-2 rounded-xl border border-slate-400"></textarea>
                        </div>
                        <div class="flex mb-3">
                            <a @click.prevent="repost(post)" href="#" class="ml-auto  items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 ">Publish</a>
                        </div>
                    </div>
            
        </div>
    </div>
</template>

<script>
export default {
    name: "Post",

    props: [
        'post',
    ],

    data() {
        return {
            title: '',
            content: '',
            is_repost: false,
            repostedId: null,
        }
    },

    methods: {
        openRepost(){
            if(this.isPersonal()) return
            this.is_repost = !this.is_repost;
        },

        repost(post){
            if(this.isPersonal()) return
            axios.post(`/api/posts/${post.id}/repost`, {title: this.title, content: this.content})
            .then( res => {
                this.title = ''
                this.content = ''
            })
        },
         
        toggleLike(post){

            axios.get(`/api/posts/${post.id}/toggle_like`)
            .then( res => {
                
                post.is_liked = res.data.is_liked
                post.likes_count = res.data.likes_count 
            })
        },

        isPersonal(){
            return this.$route.name === 'user.personal';
        }
    },

}
</script>