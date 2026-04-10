<script setup>
import { ref, nextTick, onMounted, onUnmounted } from 'vue';

const isOpen = ref(false);
const message = ref('');
const messages = ref([]);
const loading = ref(false);
const ttsLoading = ref(null);

function handleEscape(e) {
    if (e.key === 'Escape' && isOpen.value) {
        isOpen.value = false;
    }
}

onMounted(() => {
    document.addEventListener('keydown', handleEscape);
});

onUnmounted(() => {
    document.removeEventListener('keydown', handleEscape);
});

async function sendMessage() {
    const text = message.value.trim();
    if (!text || loading.value) return;

    messages.value.push({ role: 'user', content: text });
    message.value = '';
    loading.value = true;
    await scrollToBottom();

    try {
        const res = await fetch('/api/v1/groq/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ message: text }),
        });

        const data = await res.json();

        if (!res.ok) {
            messages.value.push({
                role: 'assistant',
                content: data.message || data.error || `Error: ${res.status}`,
            });
        } else {
            messages.value.push({
                role: 'assistant',
                content: data.content || data.text || JSON.stringify(data),
            });
        }
    } catch (err) {
        messages.value.push({
            role: 'assistant',
            content: `Connection error: ${err.message || 'Please try again.'}`,
        });
    }

    loading.value = false;
    await scrollToBottom();
}

async function playTts(index) {
    const msg = messages.value[index];
    if (!msg || msg.role !== 'assistant' || ttsLoading.value !== null) return;

    ttsLoading.value = index;

    try {
        const res = await fetch('/api/v1/eleven-labs/text-to-speech', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
            },
            body: JSON.stringify({ text: msg.content }),
        });

        if (!res.ok) {
            ttsLoading.value = null;
            return;
        }

        const data = await res.json();

        if (data.status && data.path) {
            const audio = new Audio(`/storage/${data.path}`);
            audio.addEventListener('ended', () => {
                ttsLoading.value = null;
            });
            audio.addEventListener('error', () => {
                ttsLoading.value = null;
            });
            audio.play();
        } else {
            ttsLoading.value = null;
        }
    } catch {
        ttsLoading.value = null;
    }
}

const messagesContainer = ref(null);

async function scrollToBottom() {
    await nextTick();
    if (messagesContainer.value) {
        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
}

function handleKeydown(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
}
</script>

<template>
    <!-- Toggle Button -->
    <button
        v-if="!isOpen"
        aria-label="Open chat assistant"
        class="fixed bottom-4 right-4 w-14 h-14 bg-indigo-600 text-white rounded-full shadow-lg hover:bg-indigo-500 flex items-center justify-center z-50 transition-transform hover:scale-105"
        @click="isOpen = true"
    >
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
            />
        </svg>
    </button>

    <!-- Chat Panel -->
    <div
        v-if="isOpen"
        role="dialog"
        aria-label="Pump Assistant chat"
        class="fixed bottom-0 right-0 sm:bottom-4 sm:right-4 w-full sm:w-96 h-full sm:h-[500px] sm:rounded-lg bg-white shadow-2xl flex flex-col z-50 border border-gray-200"
    >
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 bg-indigo-600 text-white sm:rounded-t-lg">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                    />
                </svg>
                <span class="font-semibold text-sm">Pump Assistant</span>
            </div>
            <button aria-label="Close chat" class="text-white/80 hover:text-white" @click="isOpen = false">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Messages -->
        <div ref="messagesContainer" class="flex-1 overflow-y-auto p-4 space-y-3" aria-live="polite">
            <div v-if="messages.length === 0" class="text-center text-gray-400 text-sm mt-8">
                <p>Ask me about gym equipment!</p>
                <p class="mt-1 text-xs">I can help you find the right gear.</p>
            </div>

            <div
                v-for="(msg, i) in messages"
                :key="i"
                :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'"
            >
                <div
                    :class="[
                        'max-w-[80%] rounded-lg px-3 py-2 text-sm',
                        msg.role === 'user' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-800',
                    ]"
                >
                    <p class="whitespace-pre-wrap">{{ msg.content }}</p>
                    <!-- TTS button for assistant messages -->
                    <button
                        v-if="msg.role === 'assistant'"
                        :aria-label="ttsLoading === i ? 'Playing audio' : 'Listen to response'"
                        class="mt-1 flex items-center gap-1 text-xs opacity-60 hover:opacity-100 transition-opacity"
                        :disabled="ttsLoading !== null"
                        @click="playTts(i)"
                    >
                        <svg
                            v-if="ttsLoading !== i"
                            class="w-3.5 h-3.5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15.536 8.464a5 5 0 010 7.072M17.95 6.05a8 8 0 010 11.9M6.5 8.788v6.424a.5.5 0 00.757.429l4.986-3.212a.5.5 0 000-.858L7.257 8.359a.5.5 0 00-.757.429z"
                            />
                        </svg>
                        <svg v-else class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                            />
                        </svg>
                        <span>{{ ttsLoading === i ? 'Playing...' : 'Listen' }}</span>
                    </button>
                </div>
            </div>

            <!-- Typing indicator -->
            <div v-if="loading" class="flex justify-start">
                <div class="bg-gray-100 rounded-lg px-3 py-2 text-sm text-gray-400">
                    <span class="inline-flex gap-1">
                        <span
                            class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"
                            style="animation-delay: 0ms"
                        />
                        <span
                            class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"
                            style="animation-delay: 150ms"
                        />
                        <span
                            class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"
                            style="animation-delay: 300ms"
                        />
                    </span>
                </div>
            </div>
        </div>

        <!-- Input -->
        <div class="border-t border-gray-200 p-3">
            <div class="flex gap-2">
                <label for="chat-input" class="sr-only">Message</label>
                <input
                    id="chat-input"
                    v-model="message"
                    type="text"
                    placeholder="Type a message..."
                    class="flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :disabled="loading"
                    @keydown="handleKeydown"
                />
                <button
                    aria-label="Send message"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-white hover:bg-indigo-500 disabled:opacity-50"
                    :disabled="!message.trim() || loading"
                    @click="sendMessage"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>
