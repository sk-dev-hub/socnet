<template>
    <div class="flex justify-between mb-8 text-sm text-center">
        <div>
            Подписчики
            <div>{{ stats.subscribers_count }}</div>
        </div>
        <div>
            Лайки
            <div>{{ stats.likes_count }}</div>
        </div>
        <div>
            Посты
            <div>{{ stats.posts_count }}</div>
        </div>
        <div>
            Подписки
            <div>{{ stats.followings_count }}</div>
        </div>
    </div>
</template>

<script>
export default {

    name: "Stat", 

    props: [
        'stats'
    ]

    }
</script>