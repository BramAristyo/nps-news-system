<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog';
import { Badge } from '@/components/ui/badge';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import { Plus, Pencil, Trash2 } from 'lucide-vue-next';

const props = defineProps<{
    users: any[];
}>();

const isDialogOpen = ref(false);
const editingUser = ref<any>(null);

const form = useForm({
    name: '',
    email: '',
    password: '',
    role: 'user',
    is_internal: false,
});

const openCreateDialog = () => {
    editingUser.value = null;
    form.reset();
    form.clearErrors();
    isDialogOpen.value = true;
};

const openEditDialog = (user: any) => {
    editingUser.value = user;
    form.name = user.name;
    form.email = user.email;
    form.password = ''; // Don't fill password
    form.role = user.role;
    form.is_internal = Boolean(user.is_internal);
    form.clearErrors();
    isDialogOpen.value = true;
};

const submit = () => {
    if (editingUser.value) {
        form.put(route('dashboard.users.update', editingUser.value.id), {
            onSuccess: () => isDialogOpen.value = false,
        });
    } else {
        form.post(route('dashboard.users.store'), {
            onSuccess: () => isDialogOpen.value = false,
        });
    }
};

const deleteUser = (id: number) => {
    form.delete(route('dashboard.users.destroy', id));
};
</script>

<template>
    <AdminLayout>
        <Head title="Manage Users" />
        
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-semibold md:text-2xl">Manage Users</h1>
            <Button @click="openCreateDialog" class="gap-2">
                <Plus class="h-4 w-4" />
                Add User
            </Button>
        </div>

        <div class="border rounded-lg shadow-sm">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Name</TableHead>
                        <TableHead>Email</TableHead>
                        <TableHead>Role</TableHead>
                        <TableHead>Internal Access</TableHead>
                        <TableHead class="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="user in users" :key="user.id">
                        <TableCell class="font-medium">{{ user.name }}</TableCell>
                        <TableCell>{{ user.email }}</TableCell>
                        <TableCell>
                            <Badge variant="outline">{{ user.role }}</Badge>
                        </TableCell>
                        <TableCell>
                            <Badge :variant="user.is_internal ? 'default' : 'secondary'">
                                {{ user.is_internal ? 'Yes' : 'No' }}
                            </Badge>
                        </TableCell>
                        <TableCell class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <Button variant="ghost" size="icon" @click="openEditDialog(user)">
                                    <Pencil class="h-4 w-4" />
                                </Button>
                                <ConfirmDialog @confirm="deleteUser(user.id)">
                                    <Button variant="ghost" size="icon" class="text-destructive hover:text-destructive">
                                        <Trash2 class="h-4 w-4" />
                                    </Button>
                                </ConfirmDialog>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <Dialog :open="isDialogOpen" @update:open="isDialogOpen = $event">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>{{ editingUser ? 'Edit User' : 'Add User' }}</DialogTitle>
                </DialogHeader>
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="space-y-2">
                        <Label for="name">Name</Label>
                        <Input id="name" v-model="form.name" required />
                        <p v-if="form.errors.name" class="text-sm text-destructive">{{ form.errors.name }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="email">Email</Label>
                        <Input id="email" type="email" v-model="form.email" required />
                        <p v-if="form.errors.email" class="text-sm text-destructive">{{ form.errors.email }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="password">Password {{ editingUser ? '(Leave blank to keep current)' : '' }}</Label>
                        <Input id="password" type="password" v-model="form.password" :required="!editingUser" />
                        <p v-if="form.errors.password" class="text-sm text-destructive">{{ form.errors.password }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="role">Role</Label>
                        <Select v-model="form.role">
                            <SelectTrigger>
                                <SelectValue placeholder="Select a role" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="user">User</SelectItem>
                                <SelectItem value="editor">Editor</SelectItem>
                                <SelectItem value="admin">Admin</SelectItem>
                            </SelectContent>
                        </Select>
                        <p v-if="form.errors.role" class="text-sm text-destructive">{{ form.errors.role }}</p>
                    </div>
                    <div class="flex items-center space-x-2 pt-2">
                        <Checkbox 
                            id="is_internal" 
                            :checked="form.is_internal"
                            @update:checked="(checked) => form.is_internal = checked"
                        />
                        <Label for="is_internal">Internal Access Verified</Label>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="isDialogOpen = false">Cancel</Button>
                        <Button type="submit" :disabled="form.processing">
                            {{ editingUser ? 'Update' : 'Create' }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </AdminLayout>
</template>
