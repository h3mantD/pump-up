<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';

const page = usePage();
const flash = computed(() => page.props.flash);
const visible = ref(false);
const currentMessage = ref('');
const currentType = ref('success');

let hideTimeout = null;

watch(
    flash,
    (newFlash) => {
        if (newFlash?.success) {
            currentMessage.value = newFlash.success;
            currentType.value = 'success';
            show();
        } else if (newFlash?.error) {
            currentMessage.value = newFlash.error;
            currentType.value = 'error';
            show();
        }
    },
    { immediate: true },
);

function show() {
    visible.value = true;
    clearTimeout(hideTimeout);
    hideTimeout = setTimeout(() => {
        visible.value = false;
    }, 4000);
}

function dismiss() {
    visible.value = false;
    clearTimeout(hideTimeout);
}
</script>

<template>
    <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="translate-y-2 opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-2 opacity-0"
    >
        <div
            v-if="visible"
            :class="[
                'fixed top-4 right-4 z-50 max-w-sm rounded-lg shadow-lg px-4 py-3 flex items-center gap-3',
                currentType === 'success'
                    ? 'bg-green-50 text-green-800 border border-green-200'
                    : 'bg-red-50 text-red-800 border border-red-200',
            ]"
            role="alert"
        >
            <!-- Icon -->
            <svg
                v-if="currentType === 'success'"
                class="w-5 h-5 flex-shrink-0 text-green-500"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <svg
                v-else
                class="w-5 h-5 flex-shrink-0 text-red-500"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>
            <p class="text-sm font-medium">{{ currentMessage }}</p>
            <button aria-label="Dismiss" class="ml-auto text-gray-400 hover:text-gray-600" @click="dismiss">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </Transition>
</template>
