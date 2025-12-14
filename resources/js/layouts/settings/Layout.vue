<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { toUrl, urlIsActive } from '@/lib/utils';
import { edit as editProfile } from '@/routes/profile';
import { edit as editPassword } from '@/routes/user-password';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Profile',
        href: editProfile(),
    },
    {
        title: 'Password',
        href: editPassword(),
    },
];

const currentPath = typeof window !== undefined ? window.location.pathname : '';
</script>

<template>
    <div class="px-4 py-8 md:px-8">
        <div class="max-w-6xl mx-auto">
            <div class="mb-8">
                <Heading
                    title="Account Settings"
                    description="Manage your profile information and account security"
                />
            </div>

            <div class="flex flex-col lg:flex-row lg:space-x-10">
                <aside class="w-full lg:w-64 mb-8 lg:mb-0">
                    <nav class="flex flex-col space-y-1">
                        <Button
                            v-for="item in sidebarNavItems"
                            :key="toUrl(item.href)"
                            variant="ghost"
                            :class="[
                                'w-full justify-start gap-3 h-10 px-4 rounded-lg transition-all duration-200',
                                urlIsActive(item.href, currentPath) 
                                    ? 'bg-blue-50 text-blue-700 font-semibold shadow-sm ring-1 ring-blue-100 hover:bg-blue-100 hover:text-blue-800' 
                                    : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900',
                            ]"
                            as-child
                        >
                            <Link :href="item.href">
                                <component :is="item.icon" :class="['h-4 w-4', urlIsActive(item.href, currentPath) ? 'text-blue-600' : 'text-gray-500']" />
                                {{ item.title }}
                            </Link>
                        </Button>
                    </nav>
                </aside>

                <div class="flex-1">
                    <div class="max-w-2xl">
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
