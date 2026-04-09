<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    products: Object,
    categories: Array,
    filters: Object,
});

const search = ref(props.filters?.name || '');
const selectedCategory = ref(props.filters?.category_id || '');
const selectedStatus = ref(props.filters?.status || '');

let searchTimeout = null;

function applyFilters() {
    const params = {};
    if (search.value) params.name = search.value;
    if (selectedCategory.value) params.category_id = selectedCategory.value;
    if (selectedStatus.value) params.status = selectedStatus.value;

    router.get('/products', params, {
        preserveState: true,
        preserveScroll: true,
    });
}

watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(applyFilters, 300);
});

watch([selectedCategory, selectedStatus], applyFilters);

function clearFilters() {
    search.value = '';
    selectedCategory.value = '';
    selectedStatus.value = '';
    router.get('/products', {}, { preserveState: true });
}

function goToPage(url) {
    if (url) router.get(url, {}, { preserveState: true, preserveScroll: true });
}
</script>

<template>
    <PublicLayout title="Products">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex gap-8">
                <aside class="hidden md:block w-64 flex-shrink-0">
                    <div class="bg-white rounded-lg shadow p-6 sticky top-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
                            <button
                                v-if="search || selectedCategory || selectedStatus"
                                @click="clearFilters"
                                class="text-xs text-indigo-600 hover:text-indigo-500"
                            >
                                Clear all
                            </button>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input
                                v-model="search"
                                type="text"
                                placeholder="Search products..."
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <ul class="space-y-1">
                                <li>
                                    <button
                                        @click="selectedCategory = ''"
                                        :class="[
                                            'w-full text-left px-3 py-1.5 rounded text-sm',
                                            !selectedCategory ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50'
                                        ]"
                                    >
                                        All Categories
                                    </button>
                                </li>
                                <li v-for="category in categories" :key="category.id">
                                    <button
                                        @click="selectedCategory = category.id"
                                        :class="[
                                            'w-full text-left px-3 py-1.5 rounded text-sm',
                                            selectedCategory == category.id ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50'
                                        ]"
                                    >
                                        {{ category.name }}
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <div class="flex gap-2">
                                <button
                                    @click="selectedStatus = ''"
                                    :class="[
                                        'px-3 py-1 rounded-full text-xs font-medium',
                                        !selectedStatus ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                    ]"
                                >
                                    All
                                </button>
                                <button
                                    @click="selectedStatus = 'available'"
                                    :class="[
                                        'px-3 py-1 rounded-full text-xs font-medium',
                                        selectedStatus === 'available' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                    ]"
                                >
                                    Available
                                </button>
                            </div>
                        </div>
                    </div>
                </aside>

                <div class="flex-1">
                    <div class="mb-6 flex items-center justify-between">
                        <h1 class="text-2xl font-bold text-gray-900">Products</h1>
                        <span class="text-sm text-gray-500">{{ products.total }} products</span>
                    </div>

                    <div class="md:hidden mb-4">
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search products..."
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                    </div>

                    <div v-if="products.data.length === 0" class="text-center py-12">
                        <p class="text-gray-500">No products found.</p>
                        <button @click="clearFilters" class="mt-2 text-sm text-indigo-600 hover:text-indigo-500">Clear filters</button>
                    </div>

                    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <a
                            v-for="product in products.data"
                            :key="product.id"
                            :href="`/products/${product.id}`"
                            class="bg-white rounded-lg shadow hover:shadow-md transition-shadow overflow-hidden group"
                        >
                            <div class="aspect-[4/3] bg-gray-100 flex items-center justify-center">
                                <img
                                    v-if="product.image"
                                    :src="product.image.startsWith('http') ? product.image : `/storage/${product.image}`"
                                    :alt="product.name"
                                    class="w-full h-full object-cover"
                                />
                                <svg v-else class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="p-4">
                                <div class="flex items-start justify-between">
                                    <h3 class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600">{{ product.name }}</h3>
                                    <span
                                        :class="[
                                            'ml-2 px-2 py-0.5 rounded-full text-xs font-medium flex-shrink-0',
                                            product.status === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'
                                        ]"
                                    >
                                        {{ product.status }}
                                    </span>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">{{ product.category_name }}</p>
                                <p class="mt-2 text-lg font-bold text-indigo-600">${{ Number(product.price).toFixed(2) }}</p>
                            </div>
                        </a>
                    </div>

                    <div v-if="products.last_page > 1" class="mt-8 flex justify-center gap-2">
                        <button
                            v-for="link in products.links"
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
        </div>
    </PublicLayout>
</template>
