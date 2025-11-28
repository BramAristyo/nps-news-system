<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import ArticleCard from '@/components/ArticleCard.vue';
import LoaderSkeleton from '@/components/LoaderSkeleton.vue';

defineProps<{
    news: any[]; // Using any[] for simplicity, ideally define a type
}>();
</script>

<template>
    <GuestLayout>
        <Head title="Home" />
        
        <div class="space-y-8">
            <section class="text-center space-y-4">
                <h1 class="text-4xl font-bold tracking-tight">Latest News</h1>
                <p class="text-muted-foreground max-w-2xl mx-auto">
                    Stay updated with the latest announcements and stories from NPS.
                </p>
            </section>

            <div v-if="news && news.length > 0" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <ArticleCard 
                    v-for="article in news" 
                    :key="article.id" 
                    :article="article" 
                />
            </div>
            
            <div v-else-if="news && news.length === 0" class="text-center py-12">
                <p class="text-muted-foreground">No news available at the moment.</p>
            </div>

            <div v-else>
                <LoaderSkeleton type="card" :count="6" />
            </div>
        </div>
    </GuestLayout>
</template>
