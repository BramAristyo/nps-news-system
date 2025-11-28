# PLN NPS News - Starter

## Setup

1.  **Install Dependencies**

    ```bash
    composer install
    npm install
    ```

2.  **Environment Configuration**

    ```bash
    cp .env.example .env
    php artisan key:generate
    # Set your DB credentials in .env
    ```

3.  **Database Migration & Seeding**

    ```bash
    php artisan migrate
    php artisan db:seed --class=AdminUserSeeder
    ```

4.  **Run Development Server**
    ```bash
    npm run dev
    # In another terminal:
    php artisan serve
    ```

## Build Assets

To build for production:

```bash
npm run build
```

## Project Structure

- **Backend**: Laravel 12 with Service Layer pattern.
    - Controllers: `app/Http/Controllers/Front`, `app/Http/Controllers/Dashboard`
    - Services: `app/Services`
    - Middleware: `RoleMiddleware`, `InternalUserMiddleware`
- **Frontend**: Inertia.js + Vue 3 + Tailwind CSS + Shadcn Vue.
    - Pages: `resources/js/Pages`
    - Components: `resources/js/Components`
    - Layouts: `resources/js/Layouts`

## Routes

- **Public**: `/`, `/news/{slug}`
- **Internal**: `/internal-news` (Requires login + internal verification)
- **Dashboard**: `/dashboard` (Requires login + admin/editor role)
    - News: `/dashboard/news`
    - Categories: `/dashboard/categories` (Admin only)
    - Users: `/dashboard/users` (Admin only)

## Default Users (from Seeder)

- **Admin**: `admin@nps.com` / `password`
- **Editor**: `editor@nps.com` / `password`
- **Internal**: `internal@nps.com` / `password`
- **Public**: `user@nps.com` / `password`
