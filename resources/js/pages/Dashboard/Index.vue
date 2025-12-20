<script setup lang="ts">
import AppLayout from '@/layouts/DashboardLayout.vue';
import { type BreadcrumbItem, type NewsArticle } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Users, FileText, Folder, UserCheck, Globe, Calendar } from 'lucide-vue-next';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const props = defineProps<{
    summary: {
        total_users: number;
        total_news: number;
        total_categories: number;
        total_editors: number;
        total_internal_news: number;
    };
    recentNews: NewsArticle[];
}>();

const stats = [
    {
        title: 'Total Users',
        value: props.summary.total_users,
        icon: Users,
        description: 'Registered users',
        color: 'text-blue-600',
        bgColor: 'bg-blue-50',
    },
    {
        title: 'Total News',
        value: props.summary.total_news,
        icon: FileText,
        description: 'Published articles',
        color: 'text-green-600',
        bgColor: 'bg-green-50',
    },
    {
        title: 'Categories',
        value: props.summary.total_categories,
        icon: Folder,
        description: 'News categories',
        color: 'text-purple-600',
        bgColor: 'bg-purple-50',
    },
    {
        title: 'Editors',
        value: props.summary.total_editors,
        icon: UserCheck,
        description: 'Active editors',
        color: 'text-orange-600',
        bgColor: 'bg-orange-50',
    },
    {
        title: 'Internal News',
        value: props.summary.total_internal_news,
        icon: Globe,
        description: 'Internal articles',
        color: 'text-indigo-600',
        bgColor: 'bg-indigo-50',
    },
];

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    }).format(date);
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto p-6 space-y-6">
            <!-- Welcome Section -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Dashboard</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Welcome back! Here's an overview of your news system.</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                <Card v-for="stat in stats" :key="stat.title" class="hover:shadow-lg transition-shadow">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">
                            {{ stat.title }}
                        </CardTitle>
                        <div :class="['p-2 rounded-lg', stat.bgColor]">
                            <component :is="stat.icon" :class="['h-4 w-4', stat.color]" />
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stat.value }}</div>
                        <p class="text-xs text-muted-foreground mt-1">
                            {{ stat.description }}
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Recent News Section -->
            <Card>
                <CardHeader>
                    <CardTitle>Recent News Articles</CardTitle>
                    <CardDescription>Latest published articles in the system</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="space-y-4">
                        <div
                            v-for="article in recentNews"
                            :key="article.id"
                            class="flex items-start justify-between p-4 rounded-lg border hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                        >
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                    {{ article.title }}
                                </h3>
                                <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <Users class="h-3 w-3" />
                                        {{ article.user?.name || 'Unknown' }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <Calendar class="h-3 w-3" />
                                        {{ formatDate(article.created_at) }}
                                    </span>
                                    <span
                                        v-if="article.is_internal"
                                        class="inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10"
                                    >
                                        Internal
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div v-if="recentNews.length === 0" class="text-center py-8 text-gray-500">
                            No recent news articles found.
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
