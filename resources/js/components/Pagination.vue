<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';

defineProps<{
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
}>();
</script>

<template>
    <div v-if="links.length > 3" class="flex flex-wrap justify-center gap-2 mt-8">
        <template v-for="(link, key) in links" :key="key">
            <div v-if="link.url === null" class="flex items-center justify-center px-4 py-2 text-sm text-muted-foreground border rounded-md opacity-50 cursor-not-allowed">
                <span v-html="link.label"></span>
            </div>
            <Link v-else :href="link.url">
                <Button 
                    :variant="link.active ? 'default' : 'outline'"
                    class="min-w-[40px]"
                    :class="{ 'pointer-events-none': link.active }"
                >
                    <span v-html="link.label"></span>
                </Button>
            </Link>
        </template>
    </div>
</template>
