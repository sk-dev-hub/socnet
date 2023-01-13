<template>
    <div>
        <div class="mb-8 pb-8 border-b border-gray-300">
            <h1 class="text-xl text-slate-800">{{ post.title }}</h1>
            <router-link class="text-sm text-slate-500" :to="{name: 'user.show', params: {id: post.user.id} }">{{ post.user.name }}</router-link>
            <img v-if="post.image_url" :src="post.image_url" :alt="post.title" class="my-3 w-full">
            <p class="text-slate-600">{{ post.content }}</p>

            <div v-if="post.reposted_post" class="bg-gray-50 p-4 my-4 border border-gray-100">
                <h1 class="text-xl text-slate-800">{{ post.reposted_post.title }}</h1>
                    <router-link class="text-sm text-slate-500" :to="{name: 'user.show', params: {id: post.reposted_post.user.id} }">{{ post.reposted_post.user.name }}</router-link>
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
                        <p class=" text-slate-600">{{post.reposted_by_posts_count}}</p>
                    </div>
                </div>

                <p class="text-right text-sm text-slate-400">{{ post.date }}</p>
            </div>

                    <div v-if="isRepost" class="mt-5">
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
                            <a @click.prevent="repost(post)" href="#" class="ml-auto  items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 ">Publish</a>
                        </div>
                    </div>

                    <div v-if="post.comments_count > 0 " class="mt-5  text-slate-600">
                        <p v-if="!isShowed"  @click="getComments(post)" class="cursor-pointer">Показать {{post.comments_count}} комментария</p>
                        <p v-if="isShowed"  @click="isShowed = false" class="cursor-pointer">Скрыть комментарии</p>

                        <div v-if="comments && isShowed">
                            <div v-for="comment in comments" class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex mb-2">
                                    <p class="text-sm mr-2">{{comment.user.name}}</p>   
                                    <p @click="setParentId(comment)" class="text-sm cursor-pointer text-slate-400">Ответить</p> 
                                </div>
                                <p><span v-if="comment.answered_for_user" class="text-slate-400">{{ comment.answered_for_user }},</span> {{comment.body}}</p>
                                <p class="text-right text-sm text-slate-400">{{comment.date}}</p>
                            </div>    
                        </div>
                    </div>
                    
                    <div class="mt-5">
                        <div class="mb-3">
                            <div class="flex items-center ">
                                <p v-if="comment" class="mr-2  text-slate-600">Ответ {{ comment.user.name }}</p>
                                <p v-if="comment" @click="comment = null" class="cursor-pointer text-sm  text-slate-400">Отмена</p>
                            </div>
                            <textarea v-model="body" placeholder="comment" class="mt-2 w-96 p-2 rounded-xl border border-slate-400"></textarea>
                            <div v-if="errors.body">
                                <p v-for="error in errors.body" class="mt-1 text-sm text-rose-600" >{{ error }}</p>
                            </div>
                        </div>
                        <div class="flex mb-3">
                            <a @click.prevent="storeComment(post)" href="#" class="ml-auto  items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 ">Add comment</a>
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
            isRepost: false,
            repostedId: null,
            body: '',
            errors:[],
            comments: [],
            isShowed: false,
            comment: null,
        }
    },

    methods: {

        storeComment(post){

            const commentId = this.comment ? this.comment.id : null

            axios.post(`/api/posts/${post.id}/comment`, {body: this.body, parent_id: commentId})
            .then( res => {
                this.body = ''
                this.comments.unshift(res.data.data)
                this.comment = null
                post.comments_count++
                this.isShowed = true
            })
            .catch( e => {
               //console.log(e.response.data.errors);
               this.errors = e.response.data.errors;
            })
        },

        setParentId(comment){
            this.comment = comment

        },

        getComments(post){
            axios.get(`/api/posts/${post.id}/comment`)
            .then( res => {
                this.comments = res.data.data
                this.isShowed = true
            })
        },

        openRepost(){
            if(this.isPersonal()) return
            this.isRepost = !this.isRepost;
        },

        repost(post){
            if(this.isPersonal()) return
            axios.post(`/api/posts/${post.id}/repost`, {title: this.title, content: this.content})
            .then( res => {
                this.title = ''
                this.content = ''
            })
            .catch( e => {
               this.errors = e.response.data.errors;
            })
        },
         
        toggleLike(post){

            axios.post(`/api/posts/${post.id}/toggle_like`)
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