<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import type { NewsArticle } from '@/types';
import { Link } from '@inertiajs/vue3';
import { ArrowLeft, Calendar, User, Tag } from 'lucide-vue-next';

const props = defineProps<{
    newsArticle: NewsArticle;
}>();

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const formatContent = (content: string) => {
    // Split content by paragraphs and format
    return content.split('\n').filter(p => p.trim()).map(p => p.trim());
};
</script>

<template>
    <Layout>
        <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Back Navigation -->
            <Link 
                href="/" 
                class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 font-semibold mb-8 transition-colors group"
            >
                <ArrowLeft class="w-5 h-5 group-hover:-translate-x-1 transition-transform" />
                Kembali ke Beranda
            </Link>

            <!-- Article Header -->
            <header class="mb-8">
                <!-- Categories -->
                <div v-if="newsArticle.categories && newsArticle.categories.length > 0" class="flex flex-wrap gap-2 mb-4">
                    <span
                        v-for="cat in newsArticle.categories"
                        :key="cat.id"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg text-sm font-semibold uppercase tracking-wide hover:-translate-y-1 hover:shadow-lg transition-all duration-200"
                    >
                        <Tag class="w-3.5 h-3.5" />
                        {{ cat.name }}
                    </span>
                </div>

                <!-- Title -->
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6 leading-tight">
                    {{ newsArticle.title }}
                </h1>

                <!-- Metadata -->
                <div class="flex flex-wrap gap-6 text-gray-600 border-y border-gray-200 py-4">
                    <div v-if="newsArticle.user" class="flex items-center gap-2">
                        <User class="w-5 h-5 text-indigo-600" />
                        <span class="font-semibold text-gray-800">{{ newsArticle.user.name }}</span>
                    </div>
                    <div v-if="newsArticle.created_at" class="flex items-center gap-2">
                        <Calendar class="w-5 h-5 text-indigo-600" />
                        <time :datetime="newsArticle.created_at" class="text-gray-700">
                            {{ formatDate(newsArticle.created_at) }}
                        </time>
                    </div>
                </div>
            </header>

            <!-- Featured Image -->
            <figure v-if="newsArticle.image" class="mb-10 rounded-2xl overflow-hidden shadow-2xl">
                <img
                    :src="`/${newsArticle.image}`"
                    :alt="newsArticle.title"
                    class="w-full h-auto object-cover"
                />
            </figure>

            <!-- Article Content -->
            <div class="prose prose-lg max-w-none mb-12">
                <div 
                    v-for="(paragraph, index) in formatContent(newsArticle.content)"
                    :key="index"
                    class="mb-6 text-gray-800 leading-relaxed text-lg"
                >
                    {{ paragraph }}
                </div>
            </div>

            <!-- Article Footer -->
            <footer class="border-t-2 border-gray-200 pt-8 mt-12">
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-2xl flex-shrink-0">
                            {{ newsArticle.user?.name?.charAt(0).toUpperCase() || 'N' }}
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 mb-1">
                                Ditulis oleh {{ newsArticle.user?.name || 'Unknown' }}
                            </h3>
                            <p class="text-gray-600">
                                Dipublikasikan pada {{ formatDate(newsArticle.created_at) }}
                            </p>
                        </div>
                    </div>
                </div>
            </footer>

            <!-- Back to Home Button -->
            <div class="mt-12 text-center">
                <Link 
                    href="/" 
                    class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-xl hover:from-indigo-700 hover:to-purple-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300"
                >
                    <ArrowLeft class="w-5 h-5" />
                    Baca Berita Lainnya
                </Link>
            </div>
        </article>
    </Layout>
</template>

<style scoped>
.prose {
    color: #374151;
}

.prose p {
    margin-bottom: 1.5rem;
    line-height: 1.8;
}
</style>
