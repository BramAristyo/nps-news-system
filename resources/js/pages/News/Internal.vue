<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import type { NewsPagination } from '@/types';
import { usePage, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const news = computed(() => page.props.news as NewsPagination);

const heroNews = computed(() => news.value.data[0] || null);
const featuredNews = computed(() => news.value.data.slice(1, 4));
const gridNews = computed(() => news.value.data.slice(4, 16));

const truncate = (text: string, length: number) => {
    if (!text) return '';
    return text.length > length ? text.substring(0, length) + '...' : text;
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};
</script>

<template>
    <Layout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
             <div class="text-center mb-12 pb-8 border-b-2 border-gray-200">
                <h1 class="text-5xl font-extrabold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
                    Berita Internal
                </h1>
                <p class="text-lg text-gray-600">
                    Informasi khusus untuk karyawan internal
                </p>
            </div>


            <div v-if="news.data.length > 0">
                <section v-if="heroNews" class="mb-16">
                    <Link 
                        :href="`/news/${heroNews.slug || heroNews.id}`" 
                        class="block relative h-[600px] rounded-3xl overflow-hidden shadow-2xl hover:shadow-3xl hover:-translate-y-2 transition-all duration-500 group"
                    >
                        <div class="absolute inset-0">
                            <img
                                v-if="heroNews.image"
                                :src="heroNews.image.startsWith('images/') ? `/${heroNews.image}` : `/storage/${heroNews.image}`"
                                :alt="heroNews.title"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                            />
                            <div v-else class="w-full h-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white text-2xl font-semibold">
                                No Image
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 p-8 md:p-12 text-white z-10">
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="px-3 py-1.5 bg-amber-600/80 backdrop-blur-md border border-white/30 rounded-lg text-sm font-semibold uppercase tracking-wide">
                                    Internal
                                </span>
                                <span
                                    v-for="cat in heroNews.categories"
                                    :key="cat.id"
                                    class="px-3 py-1.5 bg-white/25 backdrop-blur-md border border-white/30 rounded-lg text-sm font-semibold uppercase tracking-wide"
                                >
                                    {{ cat.name }}
                                </span>
                            </div>
                            <h2 class="text-4xl md:text-5xl font-extrabold mb-4 leading-tight drop-shadow-lg">
                                {{ heroNews.title }}
                            </h2>
                            <p class="text-lg mb-6 text-white/95 drop-shadow-md max-w-4xl">
                                {{ truncate(heroNews.content, 200) }}
                            </p>
                            <div class="flex gap-6 text-sm text-white/90">
                                <span v-if="heroNews.user" class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ heroNews.user.name }}
                                </span>
                                <span v-if="heroNews.created_at" class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ formatDate(heroNews.created_at) }}
                                </span>
                            </div>
                        </div>
                    </Link>
                </section>

                <section v-if="featuredNews.length > 0" class="mb-16">
                    <h3 class="text-3xl font-bold mb-8 text-gray-900 pb-3 border-b-4 border-slate-600 inline-block">
                        Featured Stories
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <Link
                            v-for="article in featuredNews"
                            :key="article.id"
                            :href="`/news/${article.slug || article.id}`"
                            class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group"
                        >
                            <div class="relative h-60 overflow-hidden">
                                <img
                                    v-if="article.image"
                                    :src="article.image.startsWith('images/') ? `/${article.image}` : `/storage/${article.image}`"
                                    :alt="article.title"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                />
                                <div v-else class="w-full h-full bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center text-white font-semibold">
                                    No Image
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <span class="px-3 py-1 bg-amber-100 text-amber-700 border border-amber-200 rounded-md text-xs font-semibold uppercase tracking-wide">
                                        Internal
                                    </span>
                                    <span
                                        v-for="cat in article.categories"
                                        :key="cat.id"
                                        class="px-3 py-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-md text-xs font-semibold uppercase tracking-wide hover:-translate-y-1 hover:shadow-lg transition-all duration-200"
                                    >
                                        {{ cat.name }}
                                    </span>
                                </div>
                                <h4 class="text-xl font-bold mb-3 text-gray-900 line-clamp-2">
                                    {{ article.title }}
                                </h4>
                                <p class="text-gray-600 mb-4 line-clamp-3">
                                    {{ truncate(article.content, 120) }}
                                </p>
                                <div class="flex justify-between items-center pt-4 border-t border-gray-200 text-sm text-gray-500">
                                    <span v-if="article.user" class="font-semibold text-slate-700">
                                        {{ article.user.name }}
                                    </span>
                                    <span v-if="article.created_at">
                                        {{ formatDate(article.created_at) }}
                                    </span>
                                </div>
                            </div>
                        </Link>
                    </div>
                </section>

                <section v-if="gridNews.length > 0" class="mb-16">
                    <h3 class="text-3xl font-bold mb-8 text-gray-900 pb-3 border-b-4 border-slate-600 inline-block">
                        More Stories
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <Link
                            v-for="article in gridNews"
                            :key="article.id"
                            :href="`/news/${article.slug || article.id}`"
                            class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group"
                        >
                            <div class="relative h-48 overflow-hidden">
                                <img
                                    v-if="article.image"
                                    :src="article.image.startsWith('images/') ? `/${article.image}` : `/storage/${article.image}`"
                                    :alt="article.title"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                />
                                <div v-else class="w-full h-full bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center text-white text-sm font-semibold">
                                    No Image
                                </div>
                            </div>
                            <div class="p-5">
                                <div class="flex flex-wrap gap-1.5 mb-2">
                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-700 border border-amber-200 rounded text-xs font-semibold uppercase">
                                        Internal
                                    </span>
                                    <span
                                        v-for="cat in article.categories"
                                        :key="cat.id"
                                        class="px-2 py-0.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded text-xs font-semibold uppercase"
                                    >
                                        {{ cat.name }}
                                    </span>
                                </div>
                                <h5 class="text-lg font-bold mb-2 text-gray-900 line-clamp-2">
                                    {{ article.title }}
                                </h5>
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                    {{ truncate(article.content, 100) }}
                                </p>
                                <div class="pt-3 border-t border-gray-100 text-xs text-gray-500">
                                    <span v-if="article.user" class="font-semibold text-slate-700">
                                        {{ article.user.name }}
                                    </span>
                                </div>
                            </div>
                        </Link>
                    </div>
                </section>

                <div v-if="(news as any).links" class="flex justify-center gap-2 mt-12 flex-wrap">
                    <Link
                        v-for="(link, index) in (news as any).links"
                        :key="index"
                        :href="link.url || '#'"
                        :class="[
                            'px-4 py-2 rounded-lg font-semibold transition-all duration-200',
                            link.active 
                                ? 'bg-gradient-to-r from-slate-700 to-slate-900 text-white' 
                                : 'bg-white border-2 border-gray-200 text-gray-700 hover:border-slate-600 hover:text-slate-600',
                            !link.url && 'opacity-50 cursor-not-allowed'
                        ]"
                        v-html="link.label"
                    />
                </div>
            </div>

            <div v-else class="text-center py-16">
                <div class="mb-6">
                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No Internal News Available</h3>
                <p class="text-gray-600">Check back later for internal updates and announcements.</p>
            </div>
        </div>
    </Layout>
</template>
