<script setup lang="ts">
import AppLayout from '@/layouts/DashboardLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { logout } from '@/routes';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const handleLogout = () => {
    router.post(logout());
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <button
            @click="handleLogout"
            class="absolute top-4 right-4 px-3 py-1 text-sm bg-red-500 hover:bg-red-600 text-white rounded-lg transition"
        >
            Logout
        </button>
    </AppLayout>
</template>
