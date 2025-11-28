<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AuthLayout.vue';
</script>

<template>
    <Head title="Ping" />
    <AppLayout>
    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-bold">Ping Page</h1>
        <Button>Shadcn Button</Button>
        <Card>
            <CardHeader>
                <CardTitle>Card Title</CardTitle>
            </CardHeader>
            <CardContent>
                <CardDescription>Card Description</CardDescription>
            </CardContent>
        </Card>
    </div>
    </AppLayout>
</template>