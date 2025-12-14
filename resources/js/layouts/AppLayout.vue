<script setup lang="ts">
import { home, login, register } from '@/routes';
import { internal } from '@/routes/news';
import { edit } from '@/routes/profile';
import { Category } from '@/types';
import { Link, router, usePage } from '@inertiajs/vue3';
import { Menu, X, ChevronDown, Settings, LogOut } from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';

defineProps<{
    title?: string;
    description?: string;
}>();

const page = usePage();

const categories = computed<Category[]>(() => page.props.categories.all);

const mainCategories = computed(() =>
    categories.value.filter((cat) => cat.is_main),
);

const currentDate = ref('');
const mobileMenuOpen = ref(false);

const isHomePage = computed(() => {
    return page.url === '/' || page.url.startsWith('/?');
});

const isSettingsPage = computed(() => {
    return page.url === '/settings/profile';
});

const activeCategory = computed(() => {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('category');
});

const goToCategory = (slug: string) => {
    router.get('/', { category: slug });
};

const handleMobileCategoryClick = (slug: string) => {
    mobileMenuOpen.value = false;
    goToCategory(slug);
};

const toggleMobileMenu = () => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
};

const logout = () => {
    router.post('/logout');
};

onMounted(() => {
    const now = new Date();
    currentDate.value = now.toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
});
</script>

<template>
    <div class="min-h-screen bg-white">
        <nav class="bg-blue-900">
            <div class="container mx-auto px-4">
                <div class="flex justify-between py-3 text-xs text-white">
                    <div>{{ currentDate }}</div>
                    <div>
                        <template v-if="!page.props.auth.user">
                            <Link :href="login()" class="hover:underline"
                                >Login</Link
                            >
                            <span class="mx-1">|</span>
                            <Link :href="register()" class="hover:underline"
                                >Register</Link
                            >
                        </template>
                        <template v-else>
                            <div class="relative group z-50">
                                <button class="flex items-center gap-1 hover:underline focus:outline-none py-1">
                                    Welcome, {{ page.props.auth.user.name }}
                                    <ChevronDown class="w-3 h-3" />
                                </button>
                                <div class="absolute right-0 top-full pt-1 w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right">
                                    <div class="bg-white text-gray-800 rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5">
                                        <Link
                                            :href="edit()"
                                            class="flex items-center gap-2 px-4 py-2 hover:underline text-sm transition-colors"
                                            :class="isSettingsPage ? 'text-blue-900 font-semibold' : ''"
                                        >
                                            <Settings class="w-4 h-4" />
                                            Settings
                                        </Link>
                                        <button
                                            @click="logout"
                                            class="w-full text-left flex items-center gap-2 px-4 py-2 hover:underline text-sm text-red-600 transition-colors"
                                        >
                                            <LogOut class="w-4 h-4" />
                                            Logout
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="relative py-3 text-center">
                    <Link :href="home()">
                        <h1
                            class="text-4xl font-extrabold text-white italic md:text-6xl"
                        >
                            NPS News System
                        </h1>
                    </Link>

                    <button
                        class="absolute top-1/2 right-0 -translate-y-1/2 text-white md:hidden"
                        @click="toggleMobileMenu"
                    >
                        <Menu v-if="!mobileMenuOpen" />
                        <X v-else />
                    </button>
                </div>

                <div
                    class="hidden justify-center gap-12 py-4 text-white md:flex"
                >
                    <Link 
                        :href="home()" 
                        :class="[
                            'hover:underline',
                            isHomePage && !activeCategory ? 'font-bold underline' : ''
                        ]"
                    >
                        Home
                    </Link>
                    <Link
                        v-for="cat in mainCategories"
                        :key="cat.id"
                        :href="`/?category=${cat.slug}`"
                        :class="[
                            'hover:underline',
                            activeCategory === cat.slug ? 'font-bold underline' : ''
                        ]"
                    >
                        {{ cat.name }}
                    </Link>
                    <Link
                        v-if="page.props.auth.user?.is_internal"
                        :href="internal()"
                        class="hover:underline"
                    >
                        Internal News
                    </Link>
                </div>

                <div v-if="mobileMenuOpen" class="pb-4 text-white md:hidden">
                    <Link
                        :href="home()"
                        :class="[
                            'block border-b border-blue-800 py-2',
                            isHomePage && !activeCategory ? 'font-bold bg-blue-800' : ''
                        ]"
                        @click="mobileMenuOpen = false"
                    >
                        Home
                    </Link>

                    <button
                        v-for="cat in categories"
                        :key="cat.id"
                        :class="[
                            'block w-full border-b border-blue-800 py-2 text-left hover:underline',
                            activeCategory === cat.slug ? 'font-bold bg-blue-800' : ''
                        ]"
                        @click="handleMobileCategoryClick(cat.slug)"
                    >
                        {{ cat.name }}
                    </button>

                    <Link
                        v-if="page.props.auth.user?.is_internal"
                        href="/news/internal"
                        class="block border-b border-blue-800 py-2"
                        @click="mobileMenuOpen = false"
                    >
                        Internal News
                    </Link>
                </div>
            </div>
        </nav>

        <main class="container mx-auto px-4 py-6">
            <slot />
        </main>
    </div>
</template>
