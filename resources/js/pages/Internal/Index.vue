<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import ArticleCard from '@/components/ArticleCard.vue';
import { Lock } from 'lucide-vue-next';

defineProps<{
    news: any[];
}>();
</script>

<template>
    <GuestLayout>
        <Head title="Internal News" />
        
        <div class="space-y-8">
            <section class="text-center space-y-4">
                <div class="flex items-center justify-center gap-2 text-primary">
                    <Lock class="w-6 h-6" />
                    <h1 class="text-4xl font-bold tracking-tight">Internal News</h1>
                </div>
                <p class="text-muted-foreground max-w-2xl mx-auto">
                    Exclusive updates and information for internal staff.
                </p>
            </section>

            <div v-if="news && news.length > 0" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <ArticleCard 
                    v-for="article in news" 
                    :key="article.id" 
                    :article="article" 
                />
            </div>
            
            <div v-else class="text-center py-12">
                <p class="text-muted-foreground">No internal news available.</p>
            </div>
        </div>
    </GuestLayout>
</template>
