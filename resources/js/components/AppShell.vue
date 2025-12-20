<script setup lang="ts">
import { SidebarProvider } from '@/components/ui/sidebar';
import { Toaster } from '@/components/ui/sonner';
import { usePage } from '@inertiajs/vue3';
import { watch } from 'vue';
import { toast } from 'vue-sonner';

interface Props {
    variant?: 'header' | 'sidebar';
}

defineProps<Props>();

const page = usePage();
const isOpen = page.props.sidebarOpen;

// Watch for flash messages and display toasts
watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) {
            toast.success(flash.success as string);
        }
        if (flash?.error) {
            toast.error(flash.error as string);
        }
    },
    { deep: true, immediate: true }
);
</script>

<template>
    <div v-if="variant === 'header'" class="flex min-h-screen w-full flex-col">
        <slot />
    </div>
    <SidebarProvider v-else :default-open="isOpen">
        <slot />
    </SidebarProvider>
    <Toaster position="top-right" :toastOptions="{ duration: 3000 }" class="z-[9999]" />
</template>
