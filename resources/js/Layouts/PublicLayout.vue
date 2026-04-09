<script setup>
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    title: String,
});

const page = usePage();
const user = page.props.auth?.user;
const mobileMenuOpen = ref(false);

function logout() {
    router.post('/logout');
}
</script>

<template>
    <Head :title="title" />

    <div class="min-h-screen bg-gray-50">
        <nav class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-8">
                        <a href="/products" class="text-xl font-bold text-indigo-600">Pump</a>
                        <a
                            href="/products"
                            class="hidden sm:block text-sm font-medium text-gray-700 hover:text-gray-900"
                            >Products</a
                        >
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:gap-4">
                        <template v-if="user">
                            <a href="/dashboard" class="text-sm text-gray-700 hover:text-gray-900">Dashboard</a>
                            <span class="text-sm text-gray-500">{{ user.name }}</span>
                            <button class="text-sm text-gray-500 hover:text-gray-700 cursor-pointer" @click="logout">
                                Logout
                            </button>
                        </template>
                        <template v-else>
                            <a href="/login" class="text-sm text-gray-700 hover:text-gray-900">Login</a>
                            <a
                                href="/register"
                                class="rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-indigo-500"
                                >Register</a
                            >
                        </template>
                    </div>

                    <div class="flex items-center sm:hidden">
                        <button
                            class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100"
                            @click="mobileMenuOpen = !mobileMenuOpen"
                        >
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    v-if="!mobileMenuOpen"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    v-else
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="mobileMenuOpen" class="sm:hidden border-t border-gray-200 py-3 px-4 space-y-2">
                <a href="/products" class="block text-sm font-medium text-gray-700">Products</a>
                <template v-if="user">
                    <a href="/dashboard" class="block text-sm text-gray-700">Dashboard</a>
                    <div class="text-sm text-gray-500">{{ user.name }}</div>
                    <button class="block text-sm text-gray-500 hover:text-gray-700" @click="logout">Logout</button>
                </template>
                <template v-else>
                    <a href="/login" class="block text-sm text-gray-700">Login</a>
                    <a href="/register" class="block text-sm text-indigo-600 font-medium">Register</a>
                </template>
            </div>
        </nav>

        <main>
            <slot />
        </main>
    </div>
</template>
