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
                        <a href="#multi-turn" class="text-indigo-600 hover:text-indigo-500"
                            >5. Multi-Turn Conversations</a
                        >
                    </li>
                    <li>
                        <a href="#tts" class="text-indigo-600 hover:text-indigo-500">6. Text-to-Speech (TTS)</a>
                    </li>
                    <li>
                        <a href="#architecture" class="text-indigo-600 hover:text-indigo-500"
                            >7. Architecture Overview</a
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
                            product. A <code class="bg-gray-100 px-1 rounded">ProductObserver</code> automatically
                            dispatches a queued job whenever a product is created or updated, keeping embeddings fresh.
                        </p>

                        <div class="mt-6 bg-gray-900 rounded-lg p-6 text-sm overflow-x-auto">
                            <p class="text-gray-400 mb-2">
                                // Queued embedding generation (GenerateProductEmbedding job)
                            </p>
                            <pre class="text-green-400"><code>$text = implode(' ', [
    $this->product->name,
    $this->product->description,
    $this->product->category?->name,
]);

$response = Embeddings::for([$text])
    ->dimensions(1536)
    ->generate(Lab::OpenAI, 'text-embedding-3-small');

$this->product->updateQuietly([
    'embedding' => $response->embeddings[0],
]);</code></pre>
                        </div>

                        <div class="mt-6 bg-indigo-50 border border-indigo-100 rounded-lg p-4">
                            <p class="text-sm text-indigo-800">
                                <strong>Why pgvector?</strong> Instead of using a separate vector database (like
                                ChromaDB or Pinecone), we store embeddings directly in PostgreSQL using the
                                <code class="bg-indigo-100 px-1 rounded">pgvector</code> extension. This means one
                                database for everything — no extra infrastructure, simpler deployments, and ACID
                                transactions on your vectors. Laravel provides native support with
                                <code class="bg-indigo-100 px-1 rounded"
                                    >$table->vector('embedding', dimensions: 1536)->index()</code
                                >
                                which auto-creates an HNSW index.
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
                            ordered by relevance. You can even pass a plain string — the SDK auto-generates the
                            embedding.
                        </p>

                        <div class="mt-6 bg-gray-900 rounded-lg p-6 text-sm overflow-x-auto">
                            <p class="text-gray-400 mb-2">// Similarity search — exposed as a public API endpoint</p>
                            <pre class="text-green-400"><code>// GET /api/v1/products/search?q=leg+equipment
$products = Product::similarTo('equipment for legs')->get();

// The scope uses:
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
                                    >The AI agent <strong>autonomously decides</strong> to use its SimilaritySearch tool
                                    to find relevant products</span
                                >
                            </li>
                            <li class="flex gap-3">
                                <span
                                    class="w-6 h-6 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center text-xs font-bold flex-shrink-0"
                                    >2</span
                                >
                                <span
                                    >pgvector cosine similarity query returns matching products (EZ Curl Bar, Olympic
                                    Barbell, etc.) with real data</span
                                >
                            </li>
                            <li class="flex gap-3">
                                <span
                                    class="w-6 h-6 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center text-xs font-bold flex-shrink-0"
                                    >3</span
                                >
                                <span
                                    >The LLM generates a response with <strong>clickable product links</strong>, prices,
                                    stock info, and workout recommendations — all grounded in real data</span
                                >
                            </li>
                        </ol>

                        <div class="mt-6 bg-amber-50 border border-amber-100 rounded-lg p-4">
                            <p class="text-sm text-amber-800">
                                <strong>Why RAG instead of fine-tuning?</strong> Fine-tuning trains the model on your
                                data permanently, which is expensive and becomes stale. RAG retrieves fresh data at
                                query time — when you add a new product, it's instantly searchable without retraining
                                anything. The ProductObserver automatically generates embeddings for new products.
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
                            <strong>SimilaritySearch</strong> tool and knows about all product categories. When it finds
                            products, it includes <strong>clickable links</strong> to product detail pages and category
                            filter URLs — the agent constructs these from product IDs and category data injected into
                            its instructions.
                        </p>

                        <div class="mt-6 bg-gray-900 rounded-lg p-6 text-sm overflow-x-auto">
                            <p class="text-gray-400 mb-2">
                                // ProductAssistant — agent with tools + conversation memory
                            </p>
                            <pre class="text-green-400"><code>#[Provider(Lab::Groq)]
class ProductAssistant implements Agent, Conversational, HasTools
{
    use Promptable;

    public function instructions(): string
    {
        // Dynamic: injects current categories for filter URLs
        $categories = Category::all()->map(...);
        return "You are a fitness expert for Pump...
                URL Patterns: /products/{id}, /products?category_id={id}
                Categories: {$categories}";
    }

    public function tools(): iterable
    {
        return [
            SimilaritySearch::usingModel(
                model: Product::class,
                column: 'embedding',
                minSimilarity: 0.4,
                limit: 10,
            ),
        ];
    }

    // Conversation memory for multi-turn context
    public function messages(): iterable
    {
        return $this->conversationHistory;
    }
}</code></pre>
                        </div>

                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 text-sm mb-2">User asks:</h4>
                                <p class="text-sm text-gray-600 italic">"What equipment do you have for cardio?"</p>
                                <p class="text-xs text-gray-400 mt-2">
                                    Agent uses SimilaritySearch → finds treadmills, bikes, rowers → responds with linked
                                    product names, prices, and a
                                    <a href="/products?category_id=1" class="text-indigo-500">View All Cardio</a> link
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

                <!-- 5. Multi-Turn Conversations -->
                <article id="multi-turn">
                    <div class="flex items-center gap-3 mb-4">
                        <span
                            class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold"
                            >5</span
                        >
                        <h2 class="text-2xl font-bold text-gray-900">Multi-Turn Conversations</h2>
                    </div>

                    <div class="prose prose-gray max-w-none">
                        <p class="text-gray-600 leading-relaxed">
                            A single-turn chatbot forgets everything after each message. Our
                            <strong>multi-turn implementation</strong> sends the full conversation history with each
                            request, so the agent can understand context like "show me something cheaper" or "tell me
                            more about that one."
                        </p>
                        <p class="text-gray-600 leading-relaxed mt-4">
                            The agent implements Laravel AI SDK's
                            <code class="bg-gray-100 px-1 rounded">Conversational</code> interface. The frontend sends
                            previous messages as a <code class="bg-gray-100 px-1 rounded">history</code> array, and the
                            agent converts them into <code class="bg-gray-100 px-1 rounded">Message</code> objects that
                            the LLM sees as conversation context.
                        </p>

                        <div class="mt-6 bg-gray-900 rounded-lg p-6 text-sm overflow-x-auto">
                            <p class="text-gray-400 mb-2">// Frontend sends conversation history with each message</p>
                            <pre class="text-green-400"><code>// Chat widget sends:
{
  "message": "Do you have something cheaper?",
  "history": [
    { "role": "user", "content": "What treadmills do you have?" },
    { "role": "assistant", "content": "We have the ProForm Carbon..." },
  ]
}

// Agent sees the full conversation and understands
// "cheaper" refers to treadmills from the previous exchange</code></pre>
                        </div>

                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="bg-white border border-gray-200 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-indigo-600">20</p>
                                <p class="text-xs text-gray-500 mt-1">Max messages retained</p>
                            </div>
                            <div class="bg-white border border-gray-200 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-indigo-600">0</p>
                                <p class="text-xs text-gray-500 mt-1">Server-side storage needed</p>
                            </div>
                            <div class="bg-white border border-gray-200 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-indigo-600">Stateless</p>
                                <p class="text-xs text-gray-500 mt-1">No database for chat history</p>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- 6. TTS -->
                <article id="tts">
                    <div class="flex items-center gap-3 mb-4">
                        <span
                            class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold"
                            >6</span
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

                <!-- 7. Architecture -->
                <article id="architecture">
                    <div class="flex items-center gap-3 mb-4">
                        <span
                            class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold"
                            >7</span
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
                                        <span class="ml-1 font-medium">Groq</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">TTS:</span>
                                        <span class="ml-1 font-medium">ElevenLabs</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Backend:</span>
                                        <span class="ml-1 font-medium">Laravel 13 + AI SDK</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Frontend:</span>
                                        <span class="ml-1 font-medium">Vue 3 + Inertia.js + Tailwind</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Admin:</span>
                                        <span class="ml-1 font-medium">Filament v5</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Quality:</span>
                                        <span class="ml-1 font-medium">PHPStan + ESLint + 85 tests</span>
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
                                            ><strong>Product Created/Updated</strong> → ProductObserver dispatches
                                            queued GenerateProductEmbedding job → Job calls OpenAI Embeddings API →
                                            Stores 1536-dim vector in PostgreSQL (HNSW indexed)</span
                                        >
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span
                                            class="w-6 h-6 rounded bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold flex-shrink-0"
                                            >2</span
                                        >
                                        <span
                                            ><strong>User Sends Chat Message</strong> → Frontend sends message +
                                            conversation history → ProductAssistant agent receives it → Agent decides to
                                            use SimilaritySearch tool → pgvector cosine query → Agent gets products with
                                            IDs → Generates markdown response with clickable product links</span
                                        >
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span
                                            class="w-6 h-6 rounded bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold flex-shrink-0"
                                            >3</span
                                        >
                                        <span
                                            ><strong>User Clicks "Listen"</strong> → Response text sent to ElevenLabs
                                            via AI SDK → Audio generated and stored → Browser plays MP3 inline</span
                                        >
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span
                                            class="w-6 h-6 rounded bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold flex-shrink-0"
                                            >4</span
                                        >
                                        <span
                                            ><strong>Admin Manages Products</strong> → Filament CRUD at /admin → Create,
                                            edit, delete products with image upload → Observer auto-generates new
                                            embeddings → Instantly searchable by the chat agent</span
                                        >
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                                    <h4 class="text-sm font-semibold text-gray-700">Chat Widget Features</h4>
                                </div>
                                <div class="p-4 grid grid-cols-2 gap-3 text-sm text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <svg
                                            class="w-4 h-4 text-green-500 flex-shrink-0"
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
                                        <span>Multi-turn conversation memory</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg
                                            class="w-4 h-4 text-green-500 flex-shrink-0"
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
                                        <span>Markdown rendering (tables, links)</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg
                                            class="w-4 h-4 text-green-500 flex-shrink-0"
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
                                        <span>Clickable product links in responses</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg
                                            class="w-4 h-4 text-green-500 flex-shrink-0"
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
                                        <span>Text-to-speech on every response</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg
                                            class="w-4 h-4 text-green-500 flex-shrink-0"
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
                                        <span>Quick prompt starter buttons</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg
                                            class="w-4 h-4 text-green-500 flex-shrink-0"
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
                                        <span>Copy response to clipboard</span>
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
                    equipment, follow up with "show me something cheaper", and hit "Listen" to hear the response.
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
