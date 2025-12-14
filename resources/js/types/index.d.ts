import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
    categories: CategoryShared['categories'];
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    role: 'admin' | 'editor' | 'user';
    is_internal: boolean;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

export type CategoryShared = {
    categories: {
        main: Category[];
        all: Category[];
    };
};      
export interface Category {
    id: number;
    name: string;
    slug: string;
    is_main: boolean;
    created_at: string;
    updated_at: string;
}

export interface NewsArticle {
    id: number;
    title: string;
    content: string;
    slug: string;
    image?: string;
    is_internal: boolean;
    user_id: number;
    user?: User;
    categories?: Category[];
    created_at: string;
    updated_at: string;
}

export type NewsPagination = {
    data: NewsArticle[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
};
