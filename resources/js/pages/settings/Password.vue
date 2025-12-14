<script setup lang="ts">
import PasswordController from '@/actions/App/Http/Controllers/Settings/PasswordController';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { edit } from '@/routes/user-password';
import { Form, Head } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem } from '@/types';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Password settings',
        href: edit().url,
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <SettingsLayout>
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="text-lg font-semibold text-gray-900">Update Password</h2>
                    <p class="text-sm text-gray-500">Ensure your account is using a long, random password to stay secure.</p>
                </div>
                
                <div class="p-6">
                    <Form
                        v-bind="PasswordController.update.form()"
                        :options="{
                            preserveScroll: true,
                        }"
                        reset-on-success
                        :reset-on-error="[
                            'password',
                            'password_confirmation',
                            'current_password',
                        ]"
                        class="space-y-6"
                        v-slot="{ errors, processing, recentlySuccessful }"
                    >
                        <div class="grid gap-5">
                            <div class="grid gap-2">
                                <Label for="current_password" class="text-gray-700 font-medium">Current password</Label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <Input
                                        id="current_password"
                                        name="current_password"
                                        type="password"
                                        class="pl-10 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm"
                                        autocomplete="current-password"
                                        placeholder="Enter your current password"
                                    />
                                </div>
                                <InputError :message="errors.current_password" />
                            </div>

                            <div class="border-t border-gray-100 pt-4 mt-2"></div>

                            <div class="grid gap-5 md:grid-cols-2">
                                <div class="grid gap-2">
                                    <Label for="password" class="text-gray-700 font-medium">New password</Label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                            </svg>
                                        </div>
                                        <Input
                                            id="password"
                                            name="password"
                                            type="password"
                                            class="pl-10 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm"
                                            autocomplete="new-password"
                                            placeholder="Enter new password"
                                        />
                                    </div>
                                    <InputError :message="errors.password" />
                                </div>

                                <div class="grid gap-2">
                                    <Label for="password_confirmation" class="text-gray-700 font-medium">Confirm password</Label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        </div>
                                        <Input
                                            id="password_confirmation"
                                            name="password_confirmation"
                                            type="password"
                                            class="pl-10 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm"
                                            autocomplete="new-password"
                                            placeholder="Retype new password"
                                        />
                                    </div>
                                    <InputError :message="errors.password_confirmation" />
                                </div>
                            </div>
                        </div>

                        <div class="pt-2 flex items-center justify-end border-t border-gray-100 mt-6 md:mt-8 gap-3">
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
                                data-test="update-password-button"
                            >
                                <span v-if="processing">Updating...</span>
                                <span v-else>Update Password</span>
                            </Button>
                        </div>
                    </Form>
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
