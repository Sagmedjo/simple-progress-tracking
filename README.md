# Progress Tracking

A Laravel + Vue.js application for tracking personal and professional goals. Built with modern tooling and a focus on user experience.

## Features

- 🎯 Goal setting and tracking
- 🌓 Light/Dark mode support
- 📱 Responsive design
- 🔐 User authentication
- 🎨 Modern UI with TailwindCSS

## Tech Stack

- **Backend**: Laravel 12.x
- **Frontend**: Vue 3 with TypeScript
- **UI Components**: Radix UI + Shadcn
- **Styling**: TailwindCSS
- **Charts**: Unovis
- **Icons**: Lucide

## Prerequisites

- PHP >= 8.2
- Node.js >= 18
- Composer
- SQLite (default) or other supported database

## Installation

1. Clone the repository:

```bash
git clone https://github.com/your-username/progress-tracking.git
cd progress-tracking
```

2. Install PHP dependencies:

```bash
composer install
```

3. Install Node.js dependencies:

```bash
npm install
```

4. Copy environment file and generate key:

```bash
cp .env.example .env
php artisan key:generate
```

5. Create SQLite database:

```bash
touch database/database.sqlite
```

6. Run migrations:

```bash
php artisan migrate
```

7. Start development servers:

```bash
npm run dev
```

In a separate terminal:

```bash
php artisan serve
```

## Development

For local development with hot reload:

```bash
composer dev
```

This will start:

- Laravel development server
- Vite dev server
- Queue worker

## License

[MIT License](LICENSE)
