<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth.user;
</script>

<template>
    <AuthenticatedLayout title="Dashboard">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-900">Welcome back, {{ user.name }}!</h2>
            <p class="mt-2 text-gray-600">You're logged in to Pump — your gym equipment dashboard.</p>
        </div>
    </AuthenticatedLayout>
</template>
