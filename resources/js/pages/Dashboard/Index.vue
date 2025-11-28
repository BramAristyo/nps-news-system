<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Newspaper, Users, Tags, Lock } from 'lucide-vue-next';

defineProps<{
    stats: {
        total_news: number;
        total_categories: number;
        total_editors: number;
        total_internal_news: number;
    }
}>();
</script>

<template>
    <AdminLayout>
        <Head title="Dashboard" />
        
        <div class="flex items-center">
            <h1 class="text-lg font-semibold md:text-2xl">Dashboard</h1>
        </div>

        <div class="grid gap-4 md:grid-cols-2 md:gap-8 lg:grid-cols-4">
            <Card>
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium">Total News</CardTitle>
                    <Newspaper class="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">{{ stats.total_news }}</div>
                    <p class="text-xs text-muted-foreground">Articles published</p>
                </CardContent>
            </Card>
            <Card>
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium">Internal News</CardTitle>
                    <Lock class="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">{{ stats.total_internal_news }}</div>
                    <p class="text-xs text-muted-foreground">Protected articles</p>
                </CardContent>
            </Card>
            <Card>
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium">Categories</CardTitle>
                    <Tags class="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">{{ stats.total_categories }}</div>
                    <p class="text-xs text-muted-foreground">Active categories</p>
                </CardContent>
            </Card>
            <Card>
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium">Editors</CardTitle>
                    <Users class="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">{{ stats.total_editors }}</div>
                    <p class="text-xs text-muted-foreground">Content creators</p>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>
