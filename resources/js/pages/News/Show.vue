<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Calendar, User, ArrowLeft } from 'lucide-vue-next';

defineProps<{
    article: {
        title: string;
        content: string;
        image?: string;
        created_at: string;
        user?: { name: string };
        categories?: { name: string }[];
    }
}>();

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};
</script>

<template>
    <GuestLayout>
        <Head :title="article.title" />

        <div class="max-w-3xl mx-auto space-y-8">
            <Link href="/" class="inline-flex">
                <Button variant="ghost" size="sm" class="gap-2">
                    <ArrowLeft class="w-4 h-4" />
                    Back to News
                </Button>
            </Link>

            <article class="space-y-6">
                <div class="space-y-4">
                    <div class="flex flex-wrap gap-2">
                        <Badge v-for="category in article.categories" :key="category.name" variant="secondary">
                            {{ category.name }}
                        </Badge>
                    </div>
                    
                    <h1 class="text-4xl font-bold tracking-tight">{{ article.title }}</h1>
                    
                    <div class="flex items-center gap-4 text-sm text-muted-foreground border-b pb-6">
                        <span class="flex items-center gap-1">
                            <Calendar class="w-4 h-4" />
                            {{ formatDate(article.created_at) }}
                        </span>
                        <span v-if="article.user" class="flex items-center gap-1">
                            <User class="w-4 h-4" />
                            {{ article.user.name }}
                        </span>
                    </div>
                </div>

                <div v-if="article.image" class="aspect-video w-full overflow-hidden rounded-lg border">
                    <img 
                        :src="'/storage/' + article.image" 
                        :alt="article.title"
                        class="object-cover w-full h-full"
                    />
                </div>

                <div class="prose prose-neutral dark:prose-invert max-w-none">
                    <!-- Assuming content is plain text or safe HTML. If HTML, use v-html but be careful -->
                    <div class="whitespace-pre-wrap">{{ article.content }}</div>
                </div>
            </article>
        </div>
    </GuestLayout>
</template>
