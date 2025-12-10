<script setup lang="ts">
    import { computed } from 'vue';
    import Layout from '@/layouts/AppLayout.vue';
    import { usePage } from '@inertiajs/vue3';
    import type { NewsArticle } from '@/types';
    import { logout } from '@/routes';
    import { router } from '@inertiajs/vue3';

    const page = usePage();
    const news = computed(() => page.props.news as NewsArticle[]);
    
    const handleLogout = () => {
        router.post(logout());
    }
</script>

<template>
    <Layout>
        <button
            @click="handleLogout"
            class="absolute top-4 right-4 px-3 py-1 text-sm bg-red-500 hover:bg-red-600 text-white rounded-lg transition"
        >
            Logout
        </button>
        <div>
            <h1>News Index</h1>
            <button href></button>
            <div v-if="news.length > 0">
                <div v-for="article in news" :key="article.id">
                    <h2>{{ article.title }}</h2>
                    <p>{{ article.content }}</p>
                    <p v-if="article.user">Author: {{ article.user.name }}</p>
                    <p v-if="article.image">
                        <img :src="`/storage/${article.image}`" :alt="article.title">
                    </p>
                    <div v-if="article.categories">
                        <span v-for="cat in article.categories" :key="cat.id">
                            {{ cat.name }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div v-else>
                <p>No news found</p>
            </div>
        </div>
    </Layout>
</template>