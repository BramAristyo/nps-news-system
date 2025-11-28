<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Card, CardContent, CardFooter, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Calendar, User } from 'lucide-vue-next';

defineProps<{
    article: {
        title: string;
        slug: string;
        excerpt?: string;
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
    <Card class="flex flex-col h-full overflow-hidden transition-all hover:shadow-md">
        <div v-if="article.image" class="relative aspect-video w-full overflow-hidden">
            <img 
                :src="'/storage/' + article.image" 
                :alt="article.title"
                class="object-cover w-full h-full transition-transform hover:scale-105"
            />
        </div>
        <CardHeader>
            <div class="flex flex-wrap gap-2 mb-2">
                <Badge v-for="category in article.categories" :key="category.name" variant="secondary">
                    {{ category.name }}
                </Badge>
            </div>
            <CardTitle class="line-clamp-2">
                <Link :href="route('news.show', article.slug)" class="hover:underline">
                    {{ article.title }}
                </Link>
            </CardTitle>
            <CardDescription class="flex items-center gap-4 text-xs">
                <span class="flex items-center gap-1">
                    <Calendar class="w-3 h-3" />
                    {{ formatDate(article.created_at) }}
                </span>
                <span v-if="article.user" class="flex items-center gap-1">
                    <User class="w-3 h-3" />
                    {{ article.user.name }}
                </span>
            </CardDescription>
        </CardHeader>
        <CardContent class="flex-1">
            <p class="text-sm text-muted-foreground line-clamp-3">
                {{ article.excerpt || 'No excerpt available.' }}
            </p>
        </CardContent>
        <CardFooter>
            <Link :href="route('news.show', article.slug)" class="w-full">
                <Button variant="outline" class="w-full">Read More</Button>
            </Link>
        </CardFooter>
    </Card>
</template>
