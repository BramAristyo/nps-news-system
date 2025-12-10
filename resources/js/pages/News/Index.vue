<script setup lang="ts">
    import { computed } from 'vue';
    import Layout from '@/layouts/AppLayout.vue';
    import { usePage } from '@inertiajs/vue3';
    import type { NewsArticle } from '@/types';

    const page = usePage();
    const news = computed(() => page.props.news as NewsArticle[]);
</script>

<template>
    <Layout>
        <div>
            <h1>News Index</h1>
            
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