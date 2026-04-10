<script setup>
import { ref, nextTick, onMounted, onUnmounted, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { marked } from 'marked';
import DOMPurify from 'dompurify';

const page = usePage();
const ttsEnabled = computed(() => page.props.features?.tts ?? false);

// Configure marked
const renderer = new marked.Renderer();
renderer.link = ({ href, text }) =>
    `<a href="${href}" target="_blank" rel="noopener" class="text-indigo-600 underline hover:text-indigo-500">${text}</a>`;

marked.setOptions({
    breaks: true,
    gfm: true,
    renderer,
});

const isOpen = ref(false);
const message = ref('');
const messages = ref([]);
const loading = ref(false);
const ttsLoading = ref(null);
const copiedIndex = ref(null);

const quickPrompts = [
    'What cardio equipment do you have?',
    'Show me free weights',
    'Equipment under $100',
    'What do you recommend for beginners?',
];

function renderMarkdown(text) {
    return DOMPurify.sanitize(marked.parse(text), {
        ALLOWED_TAGS: [
            'p',
            'br',
            'strong',
            'em',
            'a',
            'code',
            'pre',
            'ul',
            'ol',
            'li',
            'h1',
            'h2',
            'h3',
            'h4',
            'table',
            'thead',
            'tbody',
            'tr',
            'th',
            'td',
            'blockquote',
            'hr',
            'span',
            'div',
        ],
        ALLOWED_ATTR: ['href', 'target', 'rel', 'class'],
    });
}

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

function buildHistory() {
    return messages.value
        .filter((m) => m.role === 'user' || m.role === 'assistant')
        .map((m) => ({ role: m.role, content: m.content }));
}

async function sendMessage(text) {
    const msgText = text || message.value.trim();
    if (!msgText || loading.value) return;

    messages.value.push({ role: 'user', content: msgText, time: new Date() });
    message.value = '';
    loading.value = true;
    await scrollToBottom();

    try {
        const history = buildHistory().slice(0, -1);

        const res = await fetch('/api/v1/groq/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ message: msgText, history }),
        });

        const data = await res.json();

        if (!res.ok) {
            messages.value.push({
                role: 'assistant',
                content: data.message || data.error || `Error: ${res.status}`,
                time: new Date(),
            });
        } else {
            messages.value.push({
                role: 'assistant',
                content: data.content || data.text || JSON.stringify(data),
                time: new Date(),
            });
        }
    } catch (err) {
        messages.value.push({
            role: 'assistant',
            content: `Connection error: ${err.message || 'Please try again.'}`,
            time: new Date(),
        });
    }

    loading.value = false;
    await scrollToBottom();
}

async function copyMessage(index) {
    const msg = messages.value[index];
    if (!msg) return;

    try {
        await navigator.clipboard.writeText(msg.content);
        copiedIndex.value = index;
        setTimeout(() => {
            copiedIndex.value = null;
        }, 2000);
    } catch {
        // clipboard API not available
    }
}

function clearChat() {
    messages.value = [];
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

function formatTime(date) {
    if (!date) return '';
    return new Date(date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
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
        aria-label="PumpUp Assistant chat"
        class="fixed bottom-0 right-0 sm:bottom-4 sm:right-4 w-full sm:w-[28rem] h-full sm:h-[600px] sm:rounded-lg bg-white shadow-2xl flex flex-col z-50 border border-gray-200"
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
                <span class="font-semibold text-sm">PumpUp Assistant</span>
            </div>
            <div class="flex items-center gap-2">
                <button
                    v-if="messages.length > 0"
                    aria-label="Clear chat"
                    class="text-white/60 hover:text-white"
                    @click="clearChat"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                        />
                    </svg>
                </button>
                <button aria-label="Close chat" class="text-white/80 hover:text-white" @click="isOpen = false">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Messages -->
        <div ref="messagesContainer" class="flex-1 overflow-y-auto p-4 space-y-3" aria-live="polite">
            <!-- Empty state with quick prompts -->
            <div v-if="messages.length === 0 && !loading" class="mt-4">
                <div class="text-center text-gray-400 text-sm mb-6">
                    <p class="font-medium text-gray-600">Ask me about gym equipment!</p>
                    <p class="mt-1 text-xs">I can help you find the right gear.</p>
                </div>
                <div class="space-y-2">
                    <button
                        v-for="prompt in quickPrompts"
                        :key="prompt"
                        class="w-full text-left px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-700 hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-700 transition-colors"
                        @click="sendMessage(prompt)"
                    >
                        {{ prompt }}
                    </button>
                </div>
            </div>

            <!-- Messages -->
            <div
                v-for="(msg, i) in messages"
                :key="i"
                :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'"
            >
                <div
                    :class="[
                        'max-w-[90%] rounded-lg px-3 py-2 text-sm',
                        msg.role === 'user' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-800',
                    ]"
                >
                    <!-- User: plain text -->
                    <p v-if="msg.role === 'user'" class="whitespace-pre-wrap">{{ msg.content }}</p>

                    <!-- Assistant: rendered markdown -->
                    <div v-else class="chat-markdown" v-html="renderMarkdown(msg.content)" />

                    <!-- Action bar for assistant messages -->
                    <div
                        v-if="msg.role === 'assistant'"
                        class="mt-1.5 flex items-center gap-3 border-t border-gray-200/50 pt-1.5"
                    >
                        <!-- TTS (only shown when ElevenLabs API key is configured) -->
                        <button
                            v-if="ttsEnabled"
                            :aria-label="ttsLoading === i ? 'Playing audio' : 'Listen to response'"
                            class="flex items-center gap-1 text-xs opacity-50 hover:opacity-100 transition-opacity"
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
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                />
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                                />
                            </svg>
                            <span>{{ ttsLoading === i ? 'Playing...' : 'Listen' }}</span>
                        </button>

                        <!-- Copy -->
                        <button
                            :aria-label="copiedIndex === i ? 'Copied' : 'Copy response'"
                            class="flex items-center gap-1 text-xs opacity-50 hover:opacity-100 transition-opacity"
                            @click="copyMessage(i)"
                        >
                            <svg
                                v-if="copiedIndex !== i"
                                class="w-3.5 h-3.5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                                />
                            </svg>
                            <svg
                                v-else
                                class="w-3.5 h-3.5 text-green-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                            <span>{{ copiedIndex === i ? 'Copied!' : 'Copy' }}</span>
                        </button>

                        <!-- Timestamp -->
                        <span v-if="msg.time" class="text-xs opacity-40 ml-auto">{{ formatTime(msg.time) }}</span>
                    </div>

                    <!-- Timestamp for user messages -->
                    <div v-if="msg.role === 'user' && msg.time" class="mt-1 text-right">
                        <span class="text-xs opacity-50">{{ formatTime(msg.time) }}</span>
                    </div>
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
                    @click="sendMessage()"
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

<style scoped>
.chat-markdown :deep(h1),
.chat-markdown :deep(h2),
.chat-markdown :deep(h3),
.chat-markdown :deep(h4) {
    font-weight: 700;
    margin-top: 0.75rem;
    margin-bottom: 0.25rem;
}
.chat-markdown :deep(h3) {
    font-size: 0.875rem;
}
.chat-markdown :deep(p) {
    margin-bottom: 0.5rem;
}
.chat-markdown :deep(p:last-child) {
    margin-bottom: 0;
}
.chat-markdown :deep(ul),
.chat-markdown :deep(ol) {
    padding-left: 1.25rem;
    margin-bottom: 0.5rem;
}
.chat-markdown :deep(ul) {
    list-style-type: disc;
}
.chat-markdown :deep(ol) {
    list-style-type: decimal;
}
.chat-markdown :deep(li) {
    margin-bottom: 0.125rem;
}
.chat-markdown :deep(code) {
    background-color: rgba(0, 0, 0, 0.06);
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
    font-size: 0.8em;
}
.chat-markdown :deep(pre) {
    background-color: #1f2937;
    color: #e5e7eb;
    padding: 0.75rem;
    border-radius: 0.375rem;
    overflow-x: auto;
    margin-bottom: 0.5rem;
    font-size: 0.75rem;
    line-height: 1.5;
}
.chat-markdown :deep(pre code) {
    background: none;
    padding: 0;
    color: inherit;
    font-size: inherit;
}
.chat-markdown :deep(table) {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 0.5rem;
    font-size: 0.75rem;
}
.chat-markdown :deep(th),
.chat-markdown :deep(td) {
    border: 1px solid #d1d5db;
    padding: 0.25rem 0.5rem;
    text-align: left;
}
.chat-markdown :deep(th) {
    background-color: rgba(0, 0, 0, 0.04);
    font-weight: 600;
}
.chat-markdown :deep(strong) {
    font-weight: 600;
}
.chat-markdown :deep(em) {
    font-style: italic;
}
.chat-markdown :deep(blockquote) {
    border-left: 3px solid #d1d5db;
    padding-left: 0.75rem;
    color: #6b7280;
    margin-bottom: 0.5rem;
}
.chat-markdown :deep(hr) {
    border: none;
    border-top: 1px solid #e5e7eb;
    margin: 0.5rem 0;
}
</style>
