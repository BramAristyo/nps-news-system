<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import { Plus, Pencil, Trash2 } from 'lucide-vue-next';

const props = defineProps<{
    categories: any[];
}>();

const isDialogOpen = ref(false);
const editingCategory = ref<any>(null);

const form = useForm({
    name: '',
});

const openCreateDialog = () => {
    editingCategory.value = null;
    form.reset();
    form.clearErrors();
    isDialogOpen.value = true;
};

const openEditDialog = (category: any) => {
    editingCategory.value = category;
    form.name = category.name;
    form.clearErrors();
    isDialogOpen.value = true;
};

const submit = () => {
    if (editingCategory.value) {
        form.put(route('dashboard.categories.update', editingCategory.value.id), {
            onSuccess: () => isDialogOpen.value = false,
        });
    } else {
        form.post(route('dashboard.categories.store'), {
            onSuccess: () => isDialogOpen.value = false,
        });
    }
};

const deleteCategory = (id: number) => {
    form.delete(route('dashboard.categories.destroy', id));
};
</script>

<template>
    <AdminLayout>
        <Head title="Manage Categories" />
        
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-semibold md:text-2xl">Manage Categories</h1>
            <Button @click="openCreateDialog" class="gap-2">
                <Plus class="h-4 w-4" />
                Add Category
            </Button>
        </div>

        <div class="border rounded-lg shadow-sm">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Name</TableHead>
                        <TableHead>Slug</TableHead>
                        <TableHead class="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="category in categories" :key="category.id">
                        <TableCell class="font-medium">{{ category.name }}</TableCell>
                        <TableCell>{{ category.slug }}</TableCell>
                        <TableCell class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <Button variant="ghost" size="icon" @click="openEditDialog(category)">
                                    <Pencil class="h-4 w-4" />
                                </Button>
                                <ConfirmDialog @confirm="deleteCategory(category.id)">
                                    <Button variant="ghost" size="icon" class="text-destructive hover:text-destructive">
                                        <Trash2 class="h-4 w-4" />
                                    </Button>
                                </ConfirmDialog>
                            </div>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="categories.length === 0">
                        <TableCell colspan="3" class="text-center py-8 text-muted-foreground">
                            No categories found.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <Dialog :open="isDialogOpen" @update:open="isDialogOpen = $event">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>{{ editingCategory ? 'Edit Category' : 'Add Category' }}</DialogTitle>
                </DialogHeader>
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="space-y-2">
                        <Label for="name">Name</Label>
                        <Input id="name" v-model="form.name" required />
                        <p v-if="form.errors.name" class="text-sm text-destructive">{{ form.errors.name }}</p>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="isDialogOpen = false">Cancel</Button>
                        <Button type="submit" :disabled="form.processing">
                            {{ editingCategory ? 'Update' : 'Create' }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </AdminLayout>
</template>
