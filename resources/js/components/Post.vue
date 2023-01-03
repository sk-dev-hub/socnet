<template>
    <div>
        <div class="mb-8 pb-8 border-b border-gray-300">
            <h1 class="text-xl text-slate-800">{{ post.title }}</h1>
            <img v-if="post.image_url" :src="post.image_url" :alt="post.title" class="my-3 w-full">
            <p class="text-slate-600">{{ post.content }}</p>
            <p class="text-right text-sm text-slate-400 mt-2">{{ post.date }}</p>
        </div>
    </div>
</template>

<script>
export default {
    name: "Post",

    props: [
        'post',
    ]

}
</script>