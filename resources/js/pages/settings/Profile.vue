<script setup lang="ts">
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import { edit } from '@/routes/profile';
import { send } from '@/routes/verification';
import { Form, Head, Link, usePage } from '@inertiajs/vue3';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem } from '@/types';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: edit().url,
    },
];

const page = usePage();
const user = page.props.auth.user;
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <!-- Profile Information Card -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="text-lg font-semibold text-gray-900">Profile Information</h2>
                        <p class="text-sm text-gray-500">Update your account's profile information and email address.</p>
                    </div>
                    
                    <div class="p-6">
                        <Form
                            v-bind="ProfileController.update.form()"
                            class="space-y-6"
                            v-slot="{ errors, processing, recentlySuccessful }"
                        >
                            <div class="grid gap-5">
                                <div class="grid gap-2">
                                    <Label for="name" class="text-gray-700 font-medium">Name</Label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <Input
                                            id="name"
                                            class="pl-10 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm"
                                            name="name"
                                            :default-value="user.name"
                                            required
                                            autocomplete="name"
                                            placeholder="Your full name"
                                        />
                                    </div>
                                    <InputError class="mt-1" :message="errors.name" />
                                </div>

                                <div class="grid gap-2">
                                    <Label for="email" class="text-gray-700 font-medium">Email address</Label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <Input
                                            id="email"
                                            type="email"
                                            class="pl-10 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm"
                                            name="email"
                                            :default-value="user.email"
                                            required
                                            autocomplete="username"
                                            placeholder="name@example.com"
                                        />
                                    </div>
                                    <InputError class="mt-1" :message="errors.email" />
                                </div>
                            </div>

                            <div v-if="mustVerifyEmail && !user.email_verified_at" class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-md">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-amber-700">
                                            Your email address is unverified.
                                            <Link
                                                :href="send()"
                                                as="button"
                                                class="font-medium underline hover:text-amber-800"
                                            >
                                                Click here to resend the verification email.
                                            </Link>
                                        </p>
                                    </div>
                                </div>

                                <div
                                    v-if="status === 'verification-link-sent'"
                                    class="mt-3 text-sm font-medium text-green-600"
                                >
                                    A new verification link has been sent to your email address.
                                </div>
                            </div>

                            <div class="pt-2 flex items-center justify-end border-t border-gray-100 mt-6 gap-3">
                                <Transition
                                    enter-active-class="transition ease-in-out duration-300"
                                    enter-from-class="opacity-0"
                                    leave-active-class="transition ease-in-out duration-300"
                                    leave-to-class="opacity-0"
                                >
                                    <span v-show="recentlySuccessful" class="text-sm text-gray-500">Saved.</span>
                                </Transition>

                                <Button
                                    :disabled="processing"
                                    class="bg-blue-600 hover:bg-blue-700 text-white min-w-[100px]"
                                    data-test="update-profile-button"
                                >
                                    <span v-if="processing">Saving...</span>
                                    <span v-else>Save Changes</span>
                                </Button>
                            </div>
                        </Form>
                    </div>
                </div>

            </div>

        </SettingsLayout>
    </AppLayout>
</template>
