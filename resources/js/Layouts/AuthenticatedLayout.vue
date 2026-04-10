<script setup>
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import ChatWidget from '@/Components/ChatWidget.vue';
import FlashMessages from '@/Components/FlashMessages.vue';

defineProps({
    title: String,
});

const page = usePage();
const user = page.props.auth.user;
const mobileMenuOpen = ref(false);

function logout() {
    router.post('/logout');
}
</script>

<template>
    <Head :title="title" />

    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="/dashboard" class="text-xl font-bold text-gray-900">PumpUp</a>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:gap-4">
                        <span class="text-sm text-gray-700">{{ user.name }}</span>
                        <button class="text-sm text-gray-500 hover:text-gray-700 cursor-pointer" @click="logout">
                            Logout
                        </button>
                    </div>

                    <div class="flex items-center sm:hidden">
                        <button
                            :aria-label="mobileMenuOpen ? 'Close menu' : 'Open menu'"
                            :aria-expanded="mobileMenuOpen"
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

            <div v-if="mobileMenuOpen" class="sm:hidden border-t border-gray-200 pb-3 pt-4 px-4">
                <div class="text-sm font-medium text-gray-800">{{ user.name }}</div>
                <div class="text-sm text-gray-500">{{ user.email }}</div>
                <button class="mt-3 text-sm text-gray-500 hover:text-gray-700" @click="logout">Logout</button>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <slot />
        </main>

        <ChatWidget />
        <FlashMessages />
    </div>
</template>
