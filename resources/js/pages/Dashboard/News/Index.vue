<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import { Plus, Pencil, Trash2, Eye } from 'lucide-vue-next';

defineProps<{
    news: any[];
}>();

const deleteNews = (id: number) => {
    router.delete(route('dashboard.news.destroy', id));
};
</script>

<template>
    <AdminLayout>
        <Head title="Manage News" />
        
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-semibold md:text-2xl">Manage News</h1>
            <Link :href="route('dashboard.news.create')">
                <Button class="gap-2">
                    <Plus class="h-4 w-4" />
                    Create News
                </Button>
            </Link>
        </div>

        <div class="border rounded-lg shadow-sm">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Title</TableHead>
                        <TableHead>Author</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead>Date</TableHead>
                        <TableHead class="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="article in news" :key="article.id">
                        <TableCell class="font-medium">{{ article.title }}</TableCell>
                        <TableCell>{{ article.user?.name }}</TableCell>
                        <TableCell>
                            <Badge :variant="article.is_internal ? 'secondary' : 'default'">
                                {{ article.is_internal ? 'Internal' : 'Public' }}
                            </Badge>
                        </TableCell>
                        <TableCell>{{ new Date(article.created_at).toLocaleDateString() }}</TableCell>
                        <TableCell class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <Link :href="route('news.show', article.slug)" target="_blank">
                                    <Button variant="ghost" size="icon">
                                        <Eye class="h-4 w-4" />
                                    </Button>
                                </Link>
                                <Link :href="route('dashboard.news.edit', article.id)">
                                    <Button variant="ghost" size="icon">
                                        <Pencil class="h-4 w-4" />
                                    </Button>
                                </Link>
                                <ConfirmDialog @confirm="deleteNews(article.id)">
                                    <Button variant="ghost" size="icon" class="text-destructive hover:text-destructive">
                                        <Trash2 class="h-4 w-4" />
                                    </Button>
                                </ConfirmDialog>
                            </div>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="news.length === 0">
                        <TableCell colspan="5" class="text-center py-8 text-muted-foreground">
                            No news found. Create one to get started.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AdminLayout>
</template>
