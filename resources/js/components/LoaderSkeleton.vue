<script setup lang="ts">
import { Skeleton } from '@/components/ui/skeleton';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';

defineProps<{
    count?: number;
    type?: 'card' | 'text' | 'table';
}>();
</script>

<template>
    <div v-if="type === 'card'" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <Card v-for="i in (count || 6)" :key="i" class="flex flex-col h-full overflow-hidden">
            <Skeleton class="aspect-video w-full" />
            <CardHeader>
                <Skeleton class="h-4 w-1/3 mb-2" />
                <Skeleton class="h-6 w-3/4" />
                <Skeleton class="h-3 w-1/2 mt-2" />
            </CardHeader>
            <CardContent class="flex-1">
                <Skeleton class="h-4 w-full mb-2" />
                <Skeleton class="h-4 w-full mb-2" />
                <Skeleton class="h-4 w-2/3" />
            </CardContent>
            <CardFooter>
                <Skeleton class="h-9 w-full" />
            </CardFooter>
        </Card>
    </div>

    <div v-else-if="type === 'table'" class="space-y-4">
        <div class="flex items-center justify-between">
            <Skeleton class="h-9 w-[250px]" />
            <Skeleton class="h-9 w-[100px]" />
        </div>
        <div class="border rounded-md">
            <div class="p-4 border-b">
                <div class="flex gap-4">
                    <Skeleton class="h-4 w-[100px]" />
                    <Skeleton class="h-4 w-[200px]" />
                    <Skeleton class="h-4 w-[100px]" />
                </div>
            </div>
            <div v-for="i in (count || 5)" :key="i" class="p-4 border-b last:border-0">
                <div class="flex gap-4">
                    <Skeleton class="h-4 w-[100px]" />
                    <Skeleton class="h-4 w-[200px]" />
                    <Skeleton class="h-4 w-[100px]" />
                </div>
            </div>
        </div>
    </div>

    <div v-else class="space-y-4">
        <Skeleton class="h-8 w-3/4" />
        <Skeleton class="h-4 w-1/2" />
        <div class="space-y-2 pt-4">
            <Skeleton class="h-4 w-full" />
            <Skeleton class="h-4 w-full" />
            <Skeleton class="h-4 w-full" />
            <Skeleton class="h-4 w-2/3" />
        </div>
    </div>
</template>
