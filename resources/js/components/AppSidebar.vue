<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard, home } from '@/routes';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, User } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';
import users from '@/routes/manage/users/index';
import news from '@/routes/manage/news/index';
import categories from '@/routes/manage/categories/index';

const page = usePage();

const allNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Users',
        href: users.index(),
        icon: User,
        isAdmin: true,
    },
    {
        title: 'News',
        href: news.index(),
        icon: BookOpen,
    },
    {
        title: 'Categories',
        href: categories.index(),
        icon: Folder,
    }
];

// Filter menu items based on user role
const mainNavItems = computed(() => {
    const user = page.props.auth?.user as any;
    const isAdmin = user?.roles?.some((role: any) => role.name === 'admin') || false;
    
    return allNavItems.filter(item => {
        if (item.isAdmin) {
            return isAdmin;
        }
        return true;
    });
});

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="home()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
