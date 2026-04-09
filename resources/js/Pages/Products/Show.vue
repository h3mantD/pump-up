<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    product: Object,
    reviews: Object,
    relatedProducts: Array,
});

function goToPage(url) {
    if (url) router.get(url, {}, { preserveState: true, preserveScroll: true });
}
</script>

<template>
    <PublicLayout :title="product.name">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <nav class="mb-6 text-sm text-gray-500">
                <a href="/products" class="hover:text-gray-700">Products</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900">{{ product.name }}</span>
            </nav>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="md:flex">
                    <div class="md:w-1/2 aspect-square bg-gray-100 flex items-center justify-center">
                        <img
                            v-if="product.image"
                            :src="product.image.startsWith('http') ? product.image : `/storage/${product.image}`"
                            :alt="product.name"
                            class="w-full h-full object-cover"
                        />
                        <svg v-else class="w-24 h-24 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>

                    <div class="md:w-1/2 p-8">
                        <div class="mb-2">
                            <a
                                :href="`/products?category_id=${product.category?.id}`"
                                class="text-sm text-indigo-600 hover:text-indigo-500"
                            >
                                {{ product.category_name }}
                            </a>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ product.name }}</h1>
                        <p class="text-3xl font-bold text-indigo-600 mb-6">${{ Number(product.price).toFixed(2) }}</p>

                        <div class="flex items-center gap-4 mb-6">
                            <span
                                :class="[
                                    'px-3 py-1 rounded-full text-sm font-medium',
                                    product.status === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'
                                ]"
                            >
                                {{ product.status }}
                            </span>
                            <span class="text-sm text-gray-500">{{ product.stock }} in stock</span>
                        </div>

                        <div class="prose prose-sm text-gray-600">
                            <p>{{ product.description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Reviews</h2>

                <div v-if="reviews.data.length === 0" class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
                    No reviews yet.
                </div>

                <div v-else class="space-y-4">
                    <div v-for="review in reviews.data" :key="review.id" class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 v-if="review.title" class="font-semibold text-gray-900">{{ review.title }}</h3>
                            <div v-if="review.rating" class="flex items-center gap-1">
                                <svg
                                    v-for="star in 5"
                                    :key="star"
                                    class="w-4 h-4"
                                    :class="star <= review.rating ? 'text-yellow-400' : 'text-gray-200'"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">{{ review.review }}</p>
                    </div>

                    <div v-if="reviews.last_page > 1" class="flex justify-center gap-2 mt-4">
                        <button
                            v-for="link in reviews.links"
                            :key="link.label"
                            @click="goToPage(link.url)"
                            :disabled="!link.url"
                            :class="[
                                'px-3 py-1.5 rounded text-sm',
                                link.active ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50',
                                !link.url ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                            ]"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>

            <div v-if="relatedProducts.length > 0" class="mt-10">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">You might also like</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <a
                        v-for="related in relatedProducts"
                        :key="related.id"
                        :href="`/products/${related.id}`"
                        class="bg-white rounded-lg shadow hover:shadow-md transition-shadow overflow-hidden group"
                    >
                        <div class="aspect-[4/3] bg-gray-100 flex items-center justify-center">
                            <img
                                v-if="related.image"
                                :src="related.image.startsWith('http') ? related.image : `/storage/${related.image}`"
                                :alt="related.name"
                                class="w-full h-full object-cover"
                            />
                            <svg v-else class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="p-4">
                            <h3 class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600">{{ related.name }}</h3>
                            <p class="mt-1 text-lg font-bold text-indigo-600">${{ Number(related.price).toFixed(2) }}</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
