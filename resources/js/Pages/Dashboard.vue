<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth.user;
</script>

<template>
    <AuthenticatedLayout title="Dashboard">
        <!-- Welcome -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Welcome back, {{ user.name }}!</h2>
            <p class="mt-2 text-gray-600">You're logged in to Pump — your gym equipment dashboard.</p>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="/products" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                            />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600">Browse Products</h3>
                        <p class="text-xs text-gray-500">View our gym equipment catalog</p>
                    </div>
                </div>
            </a>

            <a href="/admin" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 group-hover:text-amber-600">Admin Panel</h3>
                        <p class="text-xs text-gray-500">Manage products and categories</p>
                    </div>
                </div>
            </a>

            <a
                href="/api/v1/products"
                target="_blank"
                class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow group"
            >
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"
                            />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 group-hover:text-green-600">API</h3>
                        <p class="text-xs text-gray-500">View REST API endpoints</p>
                    </div>
                </div>
            </a>
        </div>
    </AuthenticatedLayout>
</template>
