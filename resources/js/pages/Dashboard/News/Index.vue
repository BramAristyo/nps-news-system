<script setup lang="ts">
import AppLayout from '@/layouts/DashboardLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'News',
        href: '/dashboard/news',
    },
];

</script>

<template>
    <Head title="Manage News" />

    <AppLayout :breadcrumbs="breadcrumbs">
    </AppLayout>
</template>
