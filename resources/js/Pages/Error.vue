<script setup>
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    status: Number,
});

const title = computed(() => {
    const titles = {
        403: 'Forbidden',
        404: 'Page Not Found',
        500: 'Server Error',
        503: 'Service Unavailable',
    };
    return titles[props.status] || 'Error';
});

const description = computed(() => {
    const descriptions = {
        403: "You don't have permission to access this page.",
        404: "The page you're looking for doesn't exist or has been moved.",
        500: 'Something went wrong on our end. Please try again later.',
        503: "We're doing some maintenance. Please check back soon.",
    };
    return descriptions[props.status] || 'An unexpected error occurred.';
});
</script>

<template>
    <Head :title="title" />

    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
        <div class="text-center">
            <p class="text-6xl font-bold text-indigo-600">{{ status }}</p>
            <h1 class="mt-4 text-3xl font-bold text-gray-900">{{ title }}</h1>
            <p class="mt-2 text-gray-600">{{ description }}</p>
            <div class="mt-8 flex items-center justify-center gap-4">
                <a
                    href="/products"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                >
                    Browse Products
                </a>
                <a href="/" class="text-sm font-semibold text-gray-700 hover:text-gray-900">
                    Go Home
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
    </div>
</template>
