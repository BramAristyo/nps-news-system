<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-background font-sans text-foreground antialiased">
        <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
            <div class="container flex h-14 items-center">
                <div class="mr-4 hidden md:flex">
                    <a class="mr-6 flex items-center space-x-2" href="/">
                        <span class="hidden font-bold sm:inline-block">NPS News</span>
                    </a>
                    <nav class="flex items-center space-x-6 text-sm font-medium">
                        <a class="transition-colors hover:text-foreground/80 text-foreground/60" href="/">Home</a>
                        <a class="transition-colors hover:text-foreground/80 text-foreground/60" href="/internal-news">Internal</a>
                    </nav>
                </div>
                <div class="flex flex-1 items-center justify-between space-x-2 md:justify-end">
                    <div class="w-full flex-1 md:w-auto md:flex-none">
                        <!-- Search could go here -->
                    </div>
                    <nav class="flex items-center">
                        <a href="/login" class="text-sm font-medium text-muted-foreground transition-colors hover:text-primary">
                            Login
                        </a>
                    </nav>
                </div>
            </div>
        </header>

        <main class="container py-6">
            Test
            <slot />
        </main>

        <footer class="py-6 md:px-8 md:py-0">
            <div class="container flex flex-col items-center justify-between gap-4 md:h-24 md:flex-row">
                <p class="text-balance text-center text-sm leading-loose text-muted-foreground md:text-left">
                    Built by NPS Team.
                </p>
            </div>
        </footer>
    </div>
</template>
