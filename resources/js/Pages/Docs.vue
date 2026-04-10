<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
</script>

<template>
    <PublicLayout title="GenAI Concepts">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Hero -->
            <div class="mb-16">
                <span
                    class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700 mb-4"
                >
                    Technical Deep Dive
                </span>
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 leading-tight">
                    GenAI Concepts in<br />
                    <span class="text-indigo-600">Pump</span>
                </h1>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl">
                    This project demonstrates how to integrate modern AI capabilities into a Laravel e-commerce
                    application. Here's a breakdown of every GenAI concept we implemented and how it works under the
                    hood.
                </p>
            </div>

            <!-- Table of Contents -->
            <nav class="mb-16 bg-white rounded-lg shadow p-6">
                <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">In this guide</h2>
                <ol class="space-y-2 text-sm">
                    <li>
                        <a href="#vector-embeddings" class="text-indigo-600 hover:text-indigo-500"
                            >1. Vector Embeddings &amp; pgvector</a
                        >
                    </li>
                    <li>
                        <a href="#similarity-search" class="text-indigo-600 hover:text-indigo-500"
                            >2. Semantic Similarity Search</a
                        >
                    </li>
                    <li>
                        <a href="#rag" class="text-indigo-600 hover:text-indigo-500"
                            >3. Retrieval-Augmented Generation (RAG)</a
                        >
                    </li>
                    <li>
                        <a href="#ai-agents" class="text-indigo-600 hover:text-indigo-500"
                            >4. AI Agents with Tool Use</a
                        >
                    </li>
                    <li>
                        <a href="#tts" class="text-indigo-600 hover:text-indigo-500">5. Text-to-Speech (TTS)</a>
                    </li>
                    <li>
                        <a href="#architecture" class="text-indigo-600 hover:text-indigo-500"
                            >6. Architecture Overview</a
                        >
                    </li>
                </ol>
            </nav>

            <!-- Articles -->
            <div class="space-y-20">
                <!-- 1. Vector Embeddings -->
                <article id="vector-embeddings">
                    <div class="flex items-center gap-3 mb-4">
                        <span
                            class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold"
                            >1</span
                        >
                        <h2 class="text-2xl font-bold text-gray-900">Vector Embeddings &amp; pgvector</h2>
                    </div>

                    <div class="prose prose-gray max-w-none">
                        <p class="text-gray-600 leading-relaxed">
                            <strong>What are embeddings?</strong> An embedding is a numerical representation of text as
                            a high-dimensional vector (array of numbers). Words and sentences with similar meanings
                            produce vectors that are close together in vector space.
                        </p>
                        <p class="text-gray-600 leading-relaxed mt-4">
                            In Pump, every product's name, description, and category are combined into a single text
                            string and sent to
                            <strong>OpenAI's text-embedding-3-small</strong> model, which returns a
                            <strong>1536-dimensional vector</strong>. This vector captures the semantic meaning of the
                            product — "heavy dumbbell for strength training" and "50kg free weight" produce similar
                            vectors even though they use different words.
                        </p>

                        <div class="mt-6 bg-gray-900 rounded-lg p-6 text-sm overflow-x-auto">
                            <p class="text-gray-400 mb-2">// How we generate embeddings (Laravel AI SDK)</p>
                            <pre class="text-green-400"><code>$response = Embeddings::for([$text])
    ->dimensions(1536)
    ->generate(Lab::OpenAI, 'text-embedding-3-small');

$product->updateQuietly([
    'embedding' => $response->embeddings[0],
]);</code></pre>
                        </div>

                        <div class="mt-6 bg-indigo-50 border border-indigo-100 rounded-lg p-4">
                            <p class="text-sm text-indigo-800">
                                <strong>Why pgvector?</strong> Instead of using a separate vector database (like
                                ChromaDB or Pinecone), we store embeddings directly in PostgreSQL using the
                                <code class="bg-indigo-100 px-1 rounded">pgvector</code> extension. This means one
                                database for everything — no extra infrastructure, simpler deployments, and ACID
                                transactions on your vectors.
                            </p>
                        </div>
                    </div>
                </article>

                <!-- 2. Similarity Search -->
                <article id="similarity-search">
                    <div class="flex items-center gap-3 mb-4">
                        <span
                            class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold"
                            >2</span
                        >
                        <h2 class="text-2xl font-bold text-gray-900">Semantic Similarity Search</h2>
                    </div>

                    <div class="prose prose-gray max-w-none">
                        <p class="text-gray-600 leading-relaxed">
                            Traditional search uses keyword matching — searching "leg equipment" won't find a product
                            called "Squat Rack." Semantic similarity search solves this by comparing the
                            <em>meaning</em> of queries against product embeddings using
                            <strong>cosine distance</strong>.
                        </p>
                        <p class="text-gray-600 leading-relaxed mt-4">
                            Laravel's AI SDK provides
                            <code class="bg-gray-100 px-1 rounded">whereVectorSimilarTo()</code>
                            which handles everything: it generates an embedding for your search query, computes cosine
                            similarity against stored vectors, filters by a minimum threshold, and returns results
                            ordered by relevance.
                        </p>

                        <div class="mt-6 bg-gray-900 rounded-lg p-6 text-sm overflow-x-auto">
                            <p class="text-gray-400 mb-2">// Similarity search in one line</p>
                            <pre class="text-green-400"><code>$products = Product::similarTo('equipment for legs')
    ->get();

// Internally uses:
// ->whereVectorSimilarTo('embedding', $text, minSimilarity: 0.4)
// The query string is automatically converted to a vector!</code></pre>
                        </div>

                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 text-sm mb-2">Keyword Search</h4>
                                <p class="text-sm text-gray-500">
                                    "leg equipment" → matches only products with "leg" AND "equipment" in their name
                                </p>
                                <p class="text-xs text-red-500 mt-2">Misses: Squat Rack, Leg Press, Resistance Bands</p>
                            </div>
                            <div class="bg-white border border-indigo-200 rounded-lg p-4">
                                <h4 class="font-semibold text-indigo-700 text-sm mb-2">Semantic Search</h4>
                                <p class="text-sm text-gray-500">
                                    "leg equipment" → understands the intent and finds related products by meaning
                                </p>
                                <p class="text-xs text-green-600 mt-2">
                                    Finds: Leg Press, Squat Stand, Resistance Bands, Treadmill
                                </p>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- 3. RAG -->
                <article id="rag">
                    <div class="flex items-center gap-3 mb-4">
                        <span
                            class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold"
                            >3</span
                        >
                        <h2 class="text-2xl font-bold text-gray-900">Retrieval-Augmented Generation (RAG)</h2>
                    </div>

                    <div class="prose prose-gray max-w-none">
                        <p class="text-gray-600 leading-relaxed">
                            RAG is the pattern of
                            <strong>retrieving relevant data</strong> from your database and
                            <strong>augmenting the LLM's prompt</strong> with it before generating a response. Instead
                            of the AI hallucinating product information, it works with your actual inventory.
                        </p>
                        <p class="text-gray-600 leading-relaxed mt-4">
                            In Pump, when a user asks "What's good for building arm muscles?", the system:
                        </p>
                        <ol class="mt-4 space-y-3 text-gray-600">
                            <li class="flex gap-3">
                                <span
                                    class="w-6 h-6 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center text-xs font-bold flex-shrink-0"
                                    >1</span
                                >
                                <span
                                    >Uses <strong>similarity search</strong> to find products related to "arm muscles"
                                    from the vector database</span
                                >
                            </li>
                            <li class="flex gap-3">
                                <span
                                    class="w-6 h-6 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center text-xs font-bold flex-shrink-0"
                                    >2</span
                                >
                                <span
                                    >Injects the matching products (with names, prices, descriptions) into the
                                    <strong>system prompt</strong></span
                                >
                            </li>
                            <li class="flex gap-3">
                                <span
                                    class="w-6 h-6 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center text-xs font-bold flex-shrink-0"
                                    >3</span
                                >
                                <span
                                    >The LLM generates a response using <strong>real product data</strong> — no
                                    hallucination</span
                                >
                            </li>
                        </ol>

                        <div class="mt-6 bg-amber-50 border border-amber-100 rounded-lg p-4">
                            <p class="text-sm text-amber-800">
                                <strong>Why RAG instead of fine-tuning?</strong> Fine-tuning trains the model on your
                                data permanently, which is expensive and becomes stale. RAG retrieves fresh data at
                                query time — when you add a new product, it's instantly searchable without retraining
                                anything.
                            </p>
                        </div>
                    </div>
                </article>

                <!-- 4. AI Agents -->
                <article id="ai-agents">
                    <div class="flex items-center gap-3 mb-4">
                        <span
                            class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold"
                            >4</span
                        >
                        <h2 class="text-2xl font-bold text-gray-900">AI Agents with Tool Use</h2>
                    </div>

                    <div class="prose prose-gray max-w-none">
                        <p class="text-gray-600 leading-relaxed">
                            An <strong>AI Agent</strong> is more than a simple chatbot — it's an LLM that can
                            <strong>decide when and how to use tools</strong> to accomplish tasks. Instead of hardcoding
                            "if user says search, run a search," the agent autonomously decides based on the
                            conversation.
                        </p>
                        <p class="text-gray-600 leading-relaxed mt-4">
                            Pump's <code class="bg-gray-100 px-1 rounded">ProductAssistant</code> agent has access to a
                            <strong>SimilaritySearch</strong> tool. When a user asks about products, the agent decides
                            to use the tool, receives the results, and formulates a natural language response. When the
                            user asks a general fitness question, the agent skips the tool entirely.
                        </p>

                        <div class="mt-6 bg-gray-900 rounded-lg p-6 text-sm overflow-x-auto">
                            <p class="text-gray-400 mb-2">// ProductAssistant agent definition</p>
                            <pre class="text-green-400"><code>#[Provider(Lab::Groq)]
class ProductAssistant implements Agent
{
    use Promptable;

    public function instructions(): string
    {
        return 'You are a fitness expert for Pump...';
    }

    public function tools(): iterable
    {
        return [
            SimilaritySearch::usingModel(Product::class, 'embedding'),
        ];
    }
}</code></pre>
                        </div>

                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 text-sm mb-2">User asks:</h4>
                                <p class="text-sm text-gray-600 italic">"What equipment do you have for cardio?"</p>
                                <p class="text-xs text-gray-400 mt-2">
                                    Agent uses SimilaritySearch tool → retrieves treadmills, bikes, rowers → responds
                                    with recommendations
                                </p>
                            </div>
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 text-sm mb-2">User asks:</h4>
                                <p class="text-sm text-gray-600 italic">"How often should I work out?"</p>
                                <p class="text-xs text-gray-400 mt-2">
                                    Agent skips the tool → answers from its fitness knowledge → no product search needed
                                </p>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- 5. TTS -->
                <article id="tts">
                    <div class="flex items-center gap-3 mb-4">
                        <span
                            class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold"
                            >5</span
                        >
                        <h2 class="text-2xl font-bold text-gray-900">Text-to-Speech (TTS)</h2>
                    </div>

                    <div class="prose prose-gray max-w-none">
                        <p class="text-gray-600 leading-relaxed">
                            Every AI response in the chat widget has a
                            <strong>"Listen"</strong> button that converts the text into natural-sounding speech using
                            <strong>ElevenLabs</strong> via the Laravel AI SDK. This demonstrates multi-modal AI output
                            — the same response is available as both text and audio.
                        </p>

                        <div class="mt-6 bg-gray-900 rounded-lg p-6 text-sm overflow-x-auto">
                            <p class="text-gray-400 mb-2">// TTS in one line (Laravel AI SDK)</p>
                            <pre class="text-green-400"><code>$audio = Audio::of('Your recommended products are...')
    ->generate(Lab::ElevenLabs);

$path = $audio->storeAs('audio.mp3', 'public');</code></pre>
                        </div>

                        <p class="text-gray-600 leading-relaxed mt-4">
                            The browser's native <code class="bg-gray-100 px-1 rounded">Audio</code> API plays the
                            generated MP3 file. The chat widget shows a loading spinner while the audio is being
                            generated, then plays it inline — no page navigation needed.
                        </p>
                    </div>
                </article>

                <!-- 6. Architecture -->
                <article id="architecture">
                    <div class="flex items-center gap-3 mb-4">
                        <span
                            class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold"
                            >6</span
                        >
                        <h2 class="text-2xl font-bold text-gray-900">Architecture Overview</h2>
                    </div>

                    <div class="prose prose-gray max-w-none">
                        <p class="text-gray-600 leading-relaxed">
                            Everything is built on <strong>Laravel 13</strong> with the official
                            <strong>Laravel AI SDK</strong>. The AI SDK provides a unified interface for multiple
                            providers — switching from OpenAI to Gemini for embeddings is a one-line config change.
                        </p>

                        <div class="mt-6 space-y-4">
                            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                                    <h4 class="text-sm font-semibold text-gray-700">Tech Stack</h4>
                                </div>
                                <div class="p-4 grid grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <span class="text-gray-500">Embeddings:</span>
                                        <span class="ml-1 font-medium">OpenAI text-embedding-3-small</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Vector DB:</span>
                                        <span class="ml-1 font-medium">PostgreSQL + pgvector</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">LLM (Chat):</span>
                                        <span class="ml-1 font-medium">Groq (Mixtral)</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">TTS:</span>
                                        <span class="ml-1 font-medium">ElevenLabs</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Framework:</span>
                                        <span class="ml-1 font-medium">Laravel 13 + AI SDK</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Frontend:</span>
                                        <span class="ml-1 font-medium">Vue 3 + Inertia.js</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                                    <h4 class="text-sm font-semibold text-gray-700">Data Flow</h4>
                                </div>
                                <div class="p-4 space-y-3 text-sm text-gray-600">
                                    <div class="flex items-start gap-3">
                                        <span
                                            class="w-6 h-6 rounded bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold flex-shrink-0"
                                            >1</span
                                        >
                                        <span
                                            ><strong>Product Created/Updated</strong> → Observer dispatches queued job →
                                            Job calls OpenAI Embeddings API → Stores 1536-dim vector in PostgreSQL</span
                                        >
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span
                                            class="w-6 h-6 rounded bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold flex-shrink-0"
                                            >2</span
                                        >
                                        <span
                                            ><strong>User Sends Chat Message</strong> → ProductAssistant agent receives
                                            it → Agent decides to use SimilaritySearch tool → pgvector cosine similarity
                                            query → Agent gets relevant products → Generates natural language
                                            response</span
                                        >
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span
                                            class="w-6 h-6 rounded bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold flex-shrink-0"
                                            >3</span
                                        >
                                        <span
                                            ><strong>User Clicks "Listen"</strong> → Response text sent to ElevenLabs →
                                            Audio generated and stored → Browser plays MP3</span
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Footer CTA -->
            <div class="mt-20 bg-indigo-600 rounded-lg p-8 text-center">
                <h2 class="text-2xl font-bold text-white">Try it yourself</h2>
                <p class="mt-2 text-indigo-200">
                    Click the chat bubble in the bottom-right corner to interact with the AI assistant. Ask about gym
                    equipment and hit "Listen" to hear the response.
                </p>
                <div class="mt-6 flex items-center justify-center gap-4">
                    <a
                        href="/products"
                        class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-indigo-600 shadow-sm hover:bg-indigo-50"
                    >
                        Browse Products
                    </a>
                    <a
                        href="https://github.com/h3mantD/pump-up"
                        target="_blank"
                        class="text-sm font-semibold text-white hover:text-indigo-200"
                    >
                        View Source on GitHub
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
