<script setup lang="ts">
import AppLayout from '@/layouts/DashboardLayout.vue';
import { type BreadcrumbItem, type Category } from '@/types';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { ArrowLeft, Image as ImageIcon } from 'lucide-vue-next';
import newsRoutes from '@/routes/manage/news/index';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'News', href: '/manage/news' },
    { title: 'Create', href: '/manage/news/create' },
];

const props = defineProps<{
    categories: Category[];
}>();

const form = useForm({
    title: '',
    content: '',
    image: null as File | null,
    is_internal: false,
    category_ids: [] as number[],
});

const imagePreview = ref<string | null>(null);

const handleImageChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file) {
        form.image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
};

const toggleCategory = (categoryId: number) => {
    const index = form.category_ids.indexOf(categoryId);
    if (index > -1) {
        form.category_ids.splice(index, 1);
    } else {
        form.category_ids.push(categoryId);
    }
};

const handleSubmit = () => {
    form.post(newsRoutes.store().url, {
        onSuccess: () => {
            router.visit('/manage/news');
        },
    });
};
</script>

<template>
    <Head title="Create News Article" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto p-6 max-w-4xl">
            <div class="flex items-center gap-4 mb-6">
                <Button variant="ghost" size="icon" @click="router.visit('/manage/news')">
                    <ArrowLeft class="h-4 w-4" />
                </Button>
                <h1 class="text-2xl font-bold">Create News Article</h1>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>News Details</CardTitle>
                    <CardDescription>Fill in the information below to create a new news article.</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="handleSubmit" class="space-y-6">
                        <!-- Title -->
                        <div class="space-y-2">
                            <Label for="title">Title <span class="text-red-500">*</span></Label>
                            <Input id="title" v-model="form.title" placeholder="Enter news title" required />
                            <div v-if="form.errors.title" class="text-sm text-red-500">{{ form.errors.title }}</div>
                        </div>

                        <!-- Content -->
                        <div class="space-y-2">
                            <Label for="content">Content <span class="text-red-500">*</span></Label>
                            <Textarea id="content" v-model="form.content" rows="10" placeholder="Write your news content here..." required />
                            <div v-if="form.errors.content" class="text-sm text-red-500">{{ form.errors.content }}</div>
                        </div>

                        <!-- Image Upload -->
                        <div class="space-y-2">
                            <Label for="image">Featured Image</Label>
                            <Input id="image" type="file" accept="image/*" @change="handleImageChange" />
                            <p class="text-xs text-muted-foreground">Maximum file size: 2MB</p>
                            <div v-if="imagePreview" class="mt-4 rounded-lg border overflow-hidden">
                                <img :src="imagePreview" alt="Preview" class="w-full h-64 object-cover" />
                            </div>
                            <div v-if="form.errors.image" class="text-sm text-red-500">{{ form.errors.image }}</div>
                        </div>

                        <!-- Categories -->
                        <div class="space-y-2">
                            <Label>Categories</Label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                <div v-for="category in categories" :key="category.id" class="flex items-center space-x-2">
                                    <input
                                        type="checkbox"
                                        :id="`cat-${category.id}`"
                                        :checked="form.category_ids.includes(category.id)"
                                        @change="toggleCategory(category.id)"
                                        class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                                    />
                                    <Label :for="`cat-${category.id}`" class="font-normal cursor-pointer">{{ category.name }}</Label>
                                </div>
                            </div>
                            <div v-if="form.errors.category_ids" class="text-sm text-red-500">{{ form.errors.category_ids }}</div>
                        </div>

                        <!-- Is Internal -->
                        <div class="flex items-center space-x-2">
                            <input
                                type="checkbox"
                                id="is_internal"
                                v-model="form.is_internal"
                                class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                            />
                            <Label for="is_internal">Internal News</Label>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-4 pt-4">
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Creating...' : 'Create News Article' }}
                            </Button>
                            <Button type="button" variant="outline" @click="router.visit('/manage/news')">
                                Cancel
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
