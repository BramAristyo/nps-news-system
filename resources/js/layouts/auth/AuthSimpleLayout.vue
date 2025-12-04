<script setup lang="ts">
import { home } from '@/routes';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { Menu, X } from 'lucide-vue-next';

defineProps<{
    title?: string;
    description?: string;
}>();

const currentDate = ref('');
const mobileMenuOpen = ref(false);

const categories = [
    { name: 'Technology', href: '#' },
    { name: 'Business', href: '#' },
    { name: 'Sports', href: '#' },
    { name: 'Entertainment', href: '#' },
    { name: 'Politics', href: '#' },
    { name: 'Health', href: '#' },
    { name: 'Science', href: '#' },
];

onMounted(() => {
    const now = new Date();
    const options: Intl.DateTimeFormatOptions = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    };
    currentDate.value = now.toLocaleDateString('id-ID', options);
});

const toggleMobileMenu = () => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
};
</script>

<template>
    <div class="min-h-screen bg-white">
        <!-- Navbar -->
        <nav class="bg-blue-900">
            <div class="container mx-auto px-4">
                <!-- Top Row: Date | Login/Register -->
                <div class="flex items-center justify-between py-3 md:py-4 text-xs">
                    <div class="text-gray-50 text-[0.65rem] md:text-xs">
                        {{ currentDate }}
                    </div>
                    <div class="flex items-center gap-2 text-gray-50 text-[0.65rem] md:text-xs">
                        <Link href="#" class="hover:underline">Login</Link>
                        <span>|</span>
                        <Link href="#" class="hover:underline">Register</Link>
                    </div>
                </div>

                <!-- Middle Row: Large Logo & Mobile Menu Button -->
                <div class="pb-3 md:pb-4 text-center relative">
                    <Link :href="home()">
                        <h1 class="text-[1.75rem] md:text-[6rem] font-extrabold tracking-tight text-white font-sans italic leading-tight">
                            NPS News System
                        </h1>
                    </Link>
                    
                    <!-- Mobile Menu Button -->
                    <button 
                        @click="toggleMobileMenu"
                        class="md:hidden absolute right-0 top-1/2 -translate-y-1/2 text-white p-2"
                        aria-label="Toggle menu"
                    >
                        <Menu v-if="!mobileMenuOpen" :size="24" />
                        <X v-else :size="24" />
                    </button>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center justify-center py-6 pb-6">
                    <div class="flex items-center gap-16 text-gray-50">
                        <Link :href="home()" class="text-sm font-medium hover:underline">
                            Home
                        </Link>
                        <Link 
                            v-for="category in categories.slice(0, 5)" 
                            :key="category.name"
                            :href="category.href" 
                            class="text-sm font-medium hover:underline"
                        >
                            {{ category.name }}
                        </Link>
                    </div>
                </div>

                <!-- Mobile Navigation Menu -->
                <div 
                    v-if="mobileMenuOpen"
                    class="md:hidden pb-4"
                >
                    <div class="flex flex-col gap-3 text-gray-50">
                        <Link 
                            :href="home()" 
                            class="text-sm font-medium hover:underline py-2 border-b border-blue-800"
                            @click="mobileMenuOpen = false"
                        >
                            Home
                        </Link>
                        <Link 
                            v-for="category in categories" 
                            :key="category.name"
                            :href="category.href" 
                            class="text-sm font-medium hover:underline py-2 border-b border-blue-800"
                            @click="mobileMenuOpen = false"
                        >
                            {{ category.name }}
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-6 md:py-8">
            <slot />
        </main>
    </div>
</template>
