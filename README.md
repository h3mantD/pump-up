# Installation

```bash
git clone https://github.com/h3mantD/laravel-ai-hackathon.git
```

## Usage

### Running Frontend

```bash
cd laravel-ai-hackathon/frontend
npm install
```

### Change the API URL in the frontend/src/api.js file

```js
import axios from "axios";

const api = axios.create({
    baseURL: "https://<backend-api-servers-url>",
});

export default api;
```

### Running Backend API

#### Install Dependencies

```bash
cd laravel-ai-hackathon
composer install
```

#### Create .env file

```bash
cp .env.example .env
```

-   Add your database credentials in .env file
-   Set following api keys

    ```bash
    GROQ_API_TOKEN=
    ELEVEN_LABS_API_TOKEN=
    ELEVEN_LABS_VOICE_ID=
    ELEVEN_LABS_MODEL_ID=
    JINA_ACESS_TOKEN=
    ```

#### Generate Application Key

```bash
php artisan key:generate
```

#### Run Migrations

```bash
php artisan migrate
```

#### Run queue worker

```bash
php artisan queue:work
```

### Run Seeders

```bash
php artisan db:seed
```

#### Run Server

```bash
php artisan serve
```
