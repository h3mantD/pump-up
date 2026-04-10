# PumpUp

A gym equipment e-commerce application showcasing modern **Generative AI** concepts built with Laravel 13 and the official Laravel AI SDK.

**Live demo features:** AI-powered product assistant with vector similarity search, text-to-speech, multi-turn conversations, and an admin panel — all integrated into a Vue.js storefront.

## GenAI Concepts Demonstrated

| Concept | Implementation |
|---------|---------------|
| **Vector Embeddings** | Product text → OpenAI `text-embedding-3-small` (1536 dims) → stored in PostgreSQL via pgvector |
| **Semantic Search** | Cosine similarity via `whereVectorSimilarTo()` — "leg equipment" finds Squat Racks and Leg Presses |
| **RAG** | AI agent retrieves real product data before generating responses — no hallucination |
| **AI Agents + Tools** | `ProductAssistant` agent autonomously decides when to search products using `SimilaritySearch` tool |
| **Multi-Turn Chat** | Conversation history sent with each request — agent remembers context ("show me something cheaper") |
| **Text-to-Speech** | ElevenLabs TTS via Laravel AI SDK — listen to any AI response |

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 13, PHP 8.4 |
| AI | Laravel AI SDK (Groq, OpenAI, ElevenLabs) |
| Vector DB | PostgreSQL + pgvector |
| Frontend | Vue 3 + Inertia.js + Tailwind CSS v4 |
| Admin | Filament v5 |
| Quality | PHPStan (max level), ESLint, Prettier, Pint, 85 tests |

## Features

- **Public Storefront** — Product grid with sidebar filters, product detail with reviews and AI-powered "related products"
- **AI Chat Widget** — Floating chat on every page with markdown rendering, product links, quick prompts, copy button, TTS
- **Admin Panel** — Filament CRUD for products and categories with image upload, bulk actions, role-based access
- **REST API** — Full product CRUD, search, auth (Sanctum), chat, and TTS endpoints
- **Auto Embeddings** — Observer + queued job auto-generates embeddings when products are created/updated

## Installation

### Prerequisites

- PHP 8.4+
- PostgreSQL with [pgvector extension](https://github.com/pgvector/pgvector)
- Node.js 18+
- Composer

### Setup

```bash
git clone https://github.com/h3mantD/pump-up.git
cd pump-up

# Install dependencies
composer install
npm install

# Environment
cp .env.example .env
php artisan key:generate
```

### Configure `.env`

```bash
# Database (PostgreSQL required for pgvector)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=pump_up
DB_USERNAME=postgres
DB_PASSWORD=

# AI Providers (required)
GROQ_API_KEY=your-groq-api-key
OPENAI_API_KEY=your-openai-api-key

# Optional — enables TTS "Listen" button
ELEVENLABS_API_KEY=your-elevenlabs-api-key
```

### Database

```bash
# Ensure pgvector extension is installed
psql -U postgres -c "CREATE EXTENSION IF NOT EXISTS vector;"

# Create database
createdb -U postgres pump_up

# Run migrations and seed
php artisan migrate
php artisan db:seed
```

### Generate Product Embeddings

```bash
php artisan products:generate-embeddings
php artisan queue:work --stop-when-empty
```

### Build & Run

```bash
npm run build
php artisan serve
```

Visit `http://localhost:8000/products` to browse the storefront.

## Routes

### Public Pages

| URL | Description |
|-----|-------------|
| `/products` | Product listing with filters |
| `/products/{id}` | Product detail + reviews + related |
| `/docs` | GenAI concepts documentation |
| `/login` | Login page |
| `/register` | Registration page |

### Protected

| URL | Description |
|-----|-------------|
| `/dashboard` | User dashboard |
| `/admin` | Filament admin panel (requires `is_admin` flag) |

### API

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| `POST` | `/api/v1/login` | - | Login, returns Sanctum token |
| `POST` | `/api/v1/register` | - | Register + auto-login |
| `POST` | `/api/v1/logout` | Yes | Revoke token |
| `GET` | `/api/v1/products` | - | List products (paginated, filterable) |
| `GET` | `/api/v1/products/{id}` | - | Show product |
| `GET` | `/api/v1/products/search?q=` | - | Semantic similarity search |
| `POST` | `/api/v1/products` | Yes | Create product |
| `PUT` | `/api/v1/products/{id}` | Yes | Update product |
| `DELETE` | `/api/v1/products/{id}` | Yes | Delete product |
| `POST` | `/api/v1/products/bulk-delete` | Yes | Bulk delete |
| `PATCH` | `/api/v1/products/bulk-stock` | Yes | Bulk stock update |
| `PATCH` | `/api/v1/products/bulk-status` | Yes | Bulk status update |
| `POST` | `/api/v1/groq/chat` | - | AI chat (rate limited: 30/min) |
| `POST` | `/api/v1/eleven-labs/text-to-speech` | - | TTS (rate limited: 10/min) |

## Development

```bash
# Start dev server with hot reload
php artisan serve &
npm run dev

# Run tests
php artisan test

# Code quality
vendor/bin/phpstan analyse          # Static analysis
vendor/bin/pint                     # PHP formatting
npm run lint                        # Vue/JS linting
npm run format                      # Prettier formatting
vendor/bin/rector process --dry-run # Rector check
```

## Admin Access

The seeded admin user:
- **Email:** admin@admin.com
- **Password:** password
- **Admin panel:** `/admin`

Regular users registered via `/register` cannot access the admin panel.

## License

MIT
