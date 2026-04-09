<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    title: String,
});
</script>

<template>
    <Head :title="title" />

    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Pump</h1>
        </div>

        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
            <slot />
        </div>
    </div>
</template>
