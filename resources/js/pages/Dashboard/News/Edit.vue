<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Checkbox } from '@/components/ui/checkbox';
import { ArrowLeft } from 'lucide-vue-next';

const props = defineProps<{
    news: any;
    categories: any[];
}>();

const form = useForm({
    title: props.news.title,
    content: props.news.content,
    image: null as File | null,
    is_internal: Boolean(props.news.is_internal),
    category_ids: props.news.categories.map((c: any) => c.id),
    _method: 'PUT',
});

const submit = () => {
    form.post(route('dashboard.news.update', props.news.id));
};

const handleImageUpload = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.image = target.files[0];
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Edit News" />
        
        <div class="flex items-center gap-4">
            <Link :href="route('dashboard.news.index')">
                <Button variant="ghost" size="icon">
                    <ArrowLeft class="h-4 w-4" />
                </Button>
            </Link>
            <h1 class="text-lg font-semibold md:text-2xl">Edit News</h1>
        </div>

        <div class="max-w-2xl">
            <form @submit.prevent="submit" class="space-y-6">
                <div class="space-y-2">
                    <Label for="title">Title</Label>
                    <Input id="title" v-model="form.title" required />
                    <p v-if="form.errors.title" class="text-sm text-destructive">{{ form.errors.title }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="content">Content</Label>
                    <Textarea id="content" v-model="form.content" class="min-h-[200px]" required />
                    <p v-if="form.errors.content" class="text-sm text-destructive">{{ form.errors.content }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="image">Cover Image</Label>
                    <div v-if="news.image" class="mb-2">
                        <img :src="'/storage/' + news.image" class="h-32 w-auto object-cover rounded-md" />
                    </div>
                    <Input id="image" type="file" @change="handleImageUpload" accept="image/*" />
                    <p v-if="form.errors.image" class="text-sm text-destructive">{{ form.errors.image }}</p>
                </div>

                <div class="space-y-2">
                    <Label>Categories</Label>
                    <div class="flex flex-wrap gap-4 p-4 border rounded-md">
                        <div v-for="category in categories" :key="category.id" class="flex items-center space-x-2">
                            <Checkbox 
                                :id="'cat-' + category.id" 
                                :checked="form.category_ids.includes(category.id)"
                                @update:checked="(checked) => {
                                    if (checked) form.category_ids.push(category.id);
                                    else form.category_ids = form.category_ids.filter(id => id !== category.id);
                                }"
                            />
                            <Label :for="'cat-' + category.id">{{ category.name }}</Label>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <Checkbox 
                        id="is_internal" 
                        :checked="form.is_internal"
                        @update:checked="(checked) => form.is_internal = checked"
                    />
                    <Label for="is_internal">Internal News (Protected)</Label>
                </div>

                <div class="flex justify-end gap-4">
                    <Link :href="route('dashboard.news.index')">
                        <Button variant="outline" type="button">Cancel</Button>
                    </Link>
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Saving...' : 'Save Changes' }}
                    </Button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
