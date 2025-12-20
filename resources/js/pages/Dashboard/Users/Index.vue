<script setup lang="ts">
import AppLayout from '@/layouts/DashboardLayout.vue';
import { type BreadcrumbItem, type User } from '@/types';
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
import usersRoutes from '@/routes/manage/users/index';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/manage/users',
    },
];

const props = defineProps<{
    users: {
        data: User[];
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
            '/manage/users',
            { search: value },
            { preserveState: true, replace: true }
        );
    }, 300)
);

const isCreateOpen = ref(false);
const createForm = useForm({
    name: '',
    email: '',
    role: 'user',
    is_internal: false,
});

const handleCreate = () => {
    createForm.post(usersRoutes.store().url, {
        onSuccess: () => {
            isCreateOpen.value = false;
            createForm.reset();
            toast.success('User created successfully');
        },
        onError: () => {
             toast.error('Failed to create user');
        }
    });
};

const isEditOpen = ref(false);
const editingUser = ref<User | null>(null);
const editForm = useForm({
    name: '',
    email: '',
    role: 'user',
    is_internal: false,
});

const openEdit = (user: User) => {
    editingUser.value = user;
    editForm.name = user.name;
    editForm.email = user.email;
    editForm.role = user.role;
    editForm.is_internal = Boolean(user.is_internal);
    isEditOpen.value = true;
};

const handleEdit = () => {
    if (!editingUser.value) return;
    
    editForm.put(usersRoutes.update(editingUser.value.id).url, {
        onSuccess: () => {
            isEditOpen.value = false;
            toast.success('User updated successfully');
        },
        onError: () => {
            toast.error('Failed to update user');
        }
    });
};

const isDeleteOpen = ref(false);
const deletingUser = ref<User | null>(null);
const deleteForm = useForm({});

const openDelete = (user: User) => {
    deletingUser.value = user;
    isDeleteOpen.value = true;
};

const handleDelete = () => {
    if (!deletingUser.value) return;

    deleteForm.delete(usersRoutes.destroy(deletingUser.value.id).url, {
        onSuccess: () => {
            isDeleteOpen.value = false;
            toast.success('User deleted successfully');
        },
        onError: () => {
            toast.error('Failed to delete user');
        }
    });
};

const getRoleBadgeClass = (role: string) => {
    switch (role) {
        case 'admin':
            return 'bg-red-50 text-red-700 ring-red-700/10';
        case 'editor':
            return 'bg-blue-50 text-blue-700 ring-blue-700/10';
        default:
            return 'bg-gray-50 text-gray-600 ring-gray-500/10';
    }
};
</script>

<template>
    <Head title="Manage Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">Users</h1>
                <Dialog v-model:open="isCreateOpen">
                    <DialogTrigger as-child>
                        <Button>
                            <Plus class="w-4 h-4 mr-2" />
                            Add User
                        </Button>
                    </DialogTrigger>
                    <DialogContent>
                        <DialogHeader>
                            <DialogTitle>Create User</DialogTitle>
                            <DialogDescription>
                                Add a new user to the system. Default password will be set to "password".
                            </DialogDescription>
                        </DialogHeader>
                        <form @submit.prevent="handleCreate" class="space-y-4">
                            <div class="space-y-2">
                                <Label for="name">Name</Label>
                                <Input id="name" v-model="createForm.name" placeholder="User Name" required />
                                <div v-if="createForm.errors.name" class="text-sm text-red-500">{{ createForm.errors.name }}</div>
                            </div>
                            <div class="space-y-2">
                                <Label for="email">Email</Label>
                                <Input id="email" type="email" v-model="createForm.email" placeholder="user@example.com" required />
                                <div v-if="createForm.errors.email" class="text-sm text-red-500">{{ createForm.errors.email }}</div>
                            </div>
                            <div class="space-y-2">
                                <Label for="role">Role</Label>
                                <select id="role" v-model="createForm.role" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                    <option value="user">User</option>
                                    <option value="editor">Editor</option>
                                    <option value="admin">Admin</option>
                                </select>
                                <div v-if="createForm.errors.role" class="text-sm text-red-500">{{ createForm.errors.role }}</div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input 
                                    type="checkbox" 
                                    id="is_internal" 
                                    v-model="createForm.is_internal"
                                    class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                                />
                                <Label for="is_internal">Internal User</Label>
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
                        placeholder="Search users..."
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
                            <TableHead>Email</TableHead>
                            <TableHead>Role</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="user in users.data" :key="user.id">
                            <TableCell class="font-medium">
                                {{ user.name }}
                            </TableCell>
                            <TableCell>{{ user.email }}</TableCell>
                            <TableCell>
                                <span :class="['inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset', getRoleBadgeClass(user.role)]">
                                    {{ user.role.charAt(0).toUpperCase() + user.role.slice(1) }}
                                </span>
                            </TableCell>
                            <TableCell>
                                <span v-if="user.is_internal" class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-700/10">Internal</span>
                                <span v-else class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">External</span>
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
                                        <DropdownMenuItem @click="openEdit(user)">
                                            Edit
                                        </DropdownMenuItem>
                                        <DropdownMenuItem @click="openDelete(user)" class="text-red-600 focus:text-red-600">
                                            Delete
                                        </DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="users.data.length === 0">
                            <TableCell colspan="5" class="h-24 text-center">
                                No users found.
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <div class="flex items-center justify-end space-x-2 py-4">
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="!users.prev_page_url"
                    @click="users.prev_page_url && router.visit(users.prev_page_url)"
                >
                    Previous
                </Button>
                <div class="text-sm text-gray-500">
                     Page {{ users.current_page }} of {{ users.last_page }}
                </div>
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="!users.next_page_url"
                    @click="users.next_page_url && router.visit(users.next_page_url)"
                >
                    Next
                </Button>
            </div>

            <!-- Edit Dialog -->
            <Dialog v-model:open="isEditOpen">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Edit User</DialogTitle>
                        <DialogDescription>Make changes to the user here.</DialogDescription>
                    </DialogHeader>
                    <form @submit.prevent="handleEdit" class="space-y-4">
                        <div class="space-y-2">
                            <Label for="edit-name">Name</Label>
                            <Input id="edit-name" v-model="editForm.name" required />
                            <div v-if="editForm.errors.name" class="text-sm text-red-500">{{ editForm.errors.name }}</div>
                        </div>
                        <div class="space-y-2">
                            <Label for="edit-email">Email</Label>
                            <Input id="edit-email" type="email" v-model="editForm.email" required />
                             <div v-if="editForm.errors.email" class="text-sm text-red-500">{{ editForm.errors.email }}</div>
                        </div>
                        <div class="space-y-2">
                            <Label for="edit-role">Role</Label>
                            <select id="edit-role" v-model="editForm.role" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                <option value="user">User</option>
                                <option value="editor">Editor</option>
                                <option value="admin">Admin</option>
                            </select>
                            <div v-if="editForm.errors.role" class="text-sm text-red-500">{{ editForm.errors.role }}</div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input 
                                type="checkbox" 
                                id="edit-is_internal" 
                                v-model="editForm.is_internal"
                                class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                            />
                            <Label for="edit-is_internal">Internal User</Label>
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
                            This action cannot be undone. This will permanently delete the user
                            <strong>{{ deletingUser?.name }}</strong>.
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
