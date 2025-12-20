<script setup lang="ts">
import AppLayout from '@/layouts/DashboardLayout.vue';
import { type BreadcrumbItem } from '@/types';
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
    DialogTrigger,
} from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { toast } from 'vue-sonner';
import { MoreHorizontal, Plus, Search } from 'lucide-vue-next';
import { debounce } from 'lodash';
import categoriesRoutes from '@/routes/manage/categories/index';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Categories',
        href: '/manage/categories',
    },
];

interface Category {
    id: number;
    name: string;
    slug: string;
    is_main: boolean;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    categories: {
        data: Category[];
        current_page: number;
        first_page_url: string;
        from: number;
        last_page: number;
        last_page_url: string;
        next_page_url: string | null;
        path: string;
        per_page: number;
        prev_page_url: string | null;
        to: number;
        total: number;
        links: {
            url: string | null;
            label: string;
            active: boolean;
        }[];
    };
    filters: {
        search: string;
    };
}>();

const search = ref(props.filters.search || '');

watch(
    search,
    debounce((value: string) => {
        router.get(
            '/manage/categories',
            { search: value },
            { preserveState: true, replace: true }
        );
    }, 300)
);

const isCreateOpen = ref(false);
const createForm = useForm({
    name: '',
    is_main: false,
});

const handleCreate = () => {
    createForm.post(categoriesRoutes.store().url, {
        onSuccess: () => {
            isCreateOpen.value = false;
            createForm.reset();
            toast.success('Category created successfully');
        },
        onError: () => {
             toast.error('Failed to create category');
        }
    });
};

const isEditOpen = ref(false);
const editingCategory = ref<Category | null>(null);
const editForm = useForm({
    name: '',
    slug: '',
    is_main: false,
});

const openEdit = (category: Category) => {
    editingCategory.value = category;
    editForm.name = category.name;
    editForm.slug = category.slug;
    editForm.is_main = Boolean(category.is_main);
    isEditOpen.value = true;
};

const handleEdit = () => {
    if (!editingCategory.value) return;
    
    editForm.put(categoriesRoutes.update(editingCategory.value.id).url, {
        onSuccess: () => {
            isEditOpen.value = false;
            toast.success('Category updated successfully');
        },
        onError: () => {
            toast.error('Failed to update category');
        }
    });
};

// Delete
const isDeleteOpen = ref(false);
const deletingCategory = ref<Category | null>(null);
const deleteForm = useForm({});

const openDelete = (category: Category) => {
    deletingCategory.value = category;
    isDeleteOpen.value = true;
};

const handleDelete = () => {
    if (!deletingCategory.value) return;

    deleteForm.delete(categoriesRoutes.destroy(deletingCategory.value.id).url, {
        onSuccess: () => {
            isDeleteOpen.value = false;
            toast.success('Category deleted successfully');
        },
        onError: () => {
            toast.error('Failed to delete category');
        }
    });
};
</script>

<template>
    <Head title="Manage Categories" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">Categories</h1>
                <Dialog v-model:open="isCreateOpen">
                    <DialogTrigger as-child>
                        <Button>
                            <Plus class="w-4 h-4 mr-2" />
                            Add Category
                        </Button>
                    </DialogTrigger>
                    <DialogContent>
                        <DialogHeader>
                            <DialogTitle>Create Category</DialogTitle>
                            <DialogDescription>
                                Add a new category to the system.
                            </DialogDescription>
                        </DialogHeader>
                        <form @submit.prevent="handleCreate" class="space-y-4">
                            <div class="space-y-2">
                                <Label for="name">Name</Label>
                                <Input id="name" v-model="createForm.name" placeholder="Category Name" required />
                                <div v-if="createForm.errors.name" class="text-sm text-red-500">{{ createForm.errors.name }}</div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input 
                                    type="checkbox" 
                                    id="is_main" 
                                    v-model="createForm.is_main"
                                    class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                                />
                                <Label for="is_main">Main Category</Label>
                            </div>
                            <DialogFooter>
                                <Button type="submit" :disabled="createForm.processing">
                                    Create
                                </Button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>
            </div>

            <div class="flex items-center py-4">
                <div class="relative w-full max-w-sm">
                    <Search class="absolute left-2 top-2.5 h-4 w-4 text-muted-foreground" />
                    <Input
                        placeholder="Search categories..."
                        v-model="search"
                        class="pl-8"
                    />
                </div>
            </div>

            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead>Slug</TableHead>
                            <TableHead>Type</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="category in categories.data" :key="category.id">
                            <TableCell class="font-medium">
                                {{ category.name }}
                            </TableCell>
                            <TableCell>{{ category.slug }}</TableCell>
                            <TableCell>
                                <span v-if="category.is_main" class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">Main</span>
                                <span v-else class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Standard</span>
                            </TableCell>
                            <TableCell class="text-right">
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button variant="ghost" class="h-8 w-8 p-0">
                                            <span class="sr-only">Open menu</span>
                                            <MoreHorizontal class="h-4 w-4" />
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end">
                                        <DropdownMenuItem @click="openEdit(category)">
                                            Edit
                                        </DropdownMenuItem>
                                        <DropdownMenuItem @click="openDelete(category)" class="text-red-600 focus:text-red-600">
                                            Delete
                                        </DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="categories.data.length === 0">
                            <TableCell colspan="4" class="h-24 text-center">
                                No categories found.
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <div class="flex items-center justify-end space-x-2 py-4">
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="!categories.prev_page_url"
                    @click="categories.prev_page_url && router.visit(categories.prev_page_url)"
                >
                    Previous
                </Button>
                <div class="text-sm text-gray-500">
                     Page {{ categories.current_page }} of {{ categories.last_page }}
                </div>
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="!categories.next_page_url"
                    @click="categories.next_page_url && router.visit(categories.next_page_url)"
                >
                    Next
                </Button>
            </div>

            <!-- Edit Dialog -->
            <Dialog v-model:open="isEditOpen">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Edit Category</DialogTitle>
                        <DialogDescription>Make changes to the category here.</DialogDescription>
                    </DialogHeader>
                    <form @submit.prevent="handleEdit" class="space-y-4">
                        <div class="space-y-2">
                            <Label for="edit-name">Name</Label>
                            <Input id="edit-name" v-model="editForm.name" required />
                            <div v-if="editForm.errors.name" class="text-sm text-red-500">{{ editForm.errors.name }}</div>
                        </div>
                        <div class="space-y-2">
                            <Label for="edit-slug">Slug</Label>
                            <Input id="edit-slug" v-model="editForm.slug" required />
                             <div v-if="editForm.errors.slug" class="text-sm text-red-500">{{ editForm.errors.slug }}</div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input 
                                type="checkbox" 
                                id="edit-is_main" 
                                v-model="editForm.is_main"
                                class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                            />
                            <Label for="edit-is_main">Main Category</Label>
                        </div>
                        <DialogFooter>
                            <Button type="submit" :disabled="editForm.processing">
                                Save Changes
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>

            <!-- Delete Dialog -->
             <Dialog v-model:open="isDeleteOpen">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Are you absolutely sure?</DialogTitle>
                        <DialogDescription>
                            This action cannot be undone. This will permanently delete the category
                            <strong>{{ deletingCategory?.name }}</strong>.
                        </DialogDescription>
                    </DialogHeader>
                    <DialogFooter>
                        <Button variant="outline" @click="isDeleteOpen = false">Cancel</Button>
                        <Button variant="destructive" @click="handleDelete" :disabled="deleteForm.processing">
                            Delete
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>

        </div>
    </AppLayout>
</template>
