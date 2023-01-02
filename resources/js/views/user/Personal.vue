<template>
    <div class="w-96 mx-auto">
        <div>
            <div>
                <input v-model="title" type="text" placeholder="title" class="w-96 mb-3 p-2 rounded-xl border border-slate-400">
            </div>
            <div>
                <textarea v-model="content" placeholder="content" class="w-96 mb-3 p-2 rounded-xl border border-slate-400"></textarea>
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
    </div>
</template>

<script>

export default {
    name: "Personal",

    data() {
        return {
            title: null,
            content: null,
            image: null,
        }
    },

    methods: {
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
                console.log(res)
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
