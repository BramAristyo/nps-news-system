<script setup lang="ts">
import AppLayout from '@/layouts/DashboardLayout.vue';
import { type BreadcrumbItem, type NewsArticle, type Category } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { toast } from 'vue-sonner';
import { MoreHorizontal, Plus, Search, Image as ImageIcon } from 'lucide-vue-next';
import { debounce } from 'lodash';
import newsRoutes from '@/routes/manage/news/index';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'News',
        href: '/manage/news',
    },
];

const props = defineProps<{
    news: {
        data: NewsArticle[];
        current_page: number;
        last_page: number;
        prev_page_url: string | null;
        next_page_url: string | null;
    };
    categories: Category[];
    filters: {
        search?: string;
        category?: string;
        visibility?: string;
    };
}>();

const search = ref(props.filters.search || '');
const categoryFilter = ref(props.filters.category || '');
const visibilityFilter = ref(props.filters.visibility || 'all');

watch(
    [search, categoryFilter, visibilityFilter],
    debounce(() => {
        router.get(
            '/manage/news',
            {
                search: search.value,
                category: categoryFilter.value,
                visibility: visibilityFilter.value,
            },
            { preserveState: true, replace: true }
        );
    }, 300)
);

// Delete
const isDeleteOpen = ref(false);
const deletingNews = ref<NewsArticle | null>(null);
const deleteForm = useForm({});

const openDelete = (article: NewsArticle) => {
    deletingNews.value = article;
    isDeleteOpen.value = true;
};

const handleDelete = () => {
    if (!deletingNews.value) return;

    deleteForm.delete(newsRoutes.destroy(deletingNews.value.id).url, {
        onSuccess: () => {
            isDeleteOpen.value = false;
            toast.success('News article deleted successfully');
        },
        onError: () => {
            toast.error('Failed to delete news article');
        },
    });
};
</script>

<template>
    <Head title="Manage News" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">News Articles</h1>
                <Button @click="router.visit('/manage/news/create')">
                    <Plus class="w-4 h-4 mr-2" />
                    Add News
                </Button>
            </div>

            <!-- Filters -->
            <div class="flex items-center gap-4 py-4">
                <div class="relative flex-1 max-w-sm">
                    <Search class="absolute left-2 top-2.5 h-4 w-4 text-muted-foreground" />
                    <Input placeholder="Search news..." v-model="search" class="pl-8" />
                </div>
                <select v-model="categoryFilter" class="flex h-10 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    <option value="">All Categories</option>
                    <option v-for="category in categories" :key="category.id" :value="category.slug">
                            {{ category.name }}
                    </option>
                </select>
                <select v-model="visibilityFilter" class="flex h-10 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    <option value="all">All News</option>
                    <option value="public">Public</option>
                    <option value="internal">Internal</option>
                </select>
            </div>

            <!-- Table -->
            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-24">Image</TableHead>
                            <TableHead>Title</TableHead>
                            <TableHead>Author</TableHead>
                            <TableHead>Categories</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="article in news.data" :key="article.id">
                            <TableCell>
                                <div class="w-16 h-16 bg-gray-100 rounded-md overflow-hidden">
                                    <img v-if="article.image" :src="`/${article.image}`" :alt="article.title" class="w-full h-full object-cover" />
                                    <div v-else class="w-full h-full flex items-center justify-center">
                                        <ImageIcon class="w-6 h-6 text-gray-400" />
                                    </div>
                                </div>
                            </TableCell>
                            <TableCell class="font-medium max-w-md truncate">{{ article.title }}</TableCell>
                            <TableCell>{{ article.user?.name || 'Unknown' }}</TableCell>
                            <TableCell>
                                <div class="flex flex-wrap gap-1">
                                    <span v-for="cat in article.categories?.slice(0, 2)" :key="cat.id" class="inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                        {{ cat.name }}
                                    </span>
                                    <span v-if="article.categories && article.categories.length > 2" class="inline-flex items-center rounded-md bg-gray-50 px-2 py-0.5 text-xs font-medium text-gray-600">
                                        +{{ article.categories.length - 2 }}
                                    </span>
                                </div>
                            </TableCell>
                            <TableCell>
                                <span v-if="article.is_internal" class="inline-flex items-center rounded-md bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10">Internal</span>
                                <span v-else class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-700/10">Public</span>
                            </TableCell>
                            <TableCell class="text-right">
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button variant="ghost" class="h-8 w-8 p-0">
                                            <MoreHorizontal class="h-4 w-4" />
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end">
                                        <DropdownMenuItem @click="router.visit(`/manage/news/${article.id}/edit`)">Edit</DropdownMenuItem>
                                        <DropdownMenuItem @click="openDelete(article)" class="text-red-600 focus:text-red-600">Delete</DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="news.data.length === 0">
                            <TableCell colspan="6" class="h-24 text-center">No news articles found.</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-end space-x-2 py-4">
                <Button variant="outline" size="sm" :disabled="!news.prev_page_url" @click="news.prev_page_url && router.visit(news.prev_page_url)">Previous</Button>
                <div class="text-sm text-gray-500">Page {{ news.current_page }} of {{ news.last_page }}</div>
                <Button variant="outline" size="sm" :disabled="!news.next_page_url" @click="news.next_page_url && router.visit(news.next_page_url)">Next</Button>
            </div>

            <!-- Delete Dialog -->
            <Dialog v-model:open="isDeleteOpen">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Are you absolutely sure?</DialogTitle>
                        <DialogDescription>
                            This will permanently delete the news article <strong>{{ deletingNews?.title }}</strong>.
                        </DialogDescription>
                    </DialogHeader>
                    <DialogFooter>
                        <Button variant="outline" @click="isDeleteOpen = false">Cancel</Button>
                        <Button variant="destructive" @click="handleDelete" :disabled="deleteForm.processing">Delete</Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>
