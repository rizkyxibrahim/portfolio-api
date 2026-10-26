# Xibra API

Central REST API for Xibra Platform, powering personal productivity, scheduling, finance, and AI integrations.

## Overview

Xibra API provides the backend services for Xibra Bot, Hermes integrations, and the Portfolio Web dashboard. It manages authentication, personal productivity data, scheduling, reminders, financial records, and reporting.

This repository is part of the **Xibra Platform** ecosystem. Every application (Xibra Bot, Hermes Bot via tools, Portfolio Web dashboard) accesses data through this API with clearly designed access rights.

## Architecture Role

| Komponen | Peran |
|----------|-------|
| **Xibra API** | Backend pusat — autentikasi, aturan bisnis, akses data (sumber kebenaran tunggal) |
| **Xibra Bot** | Telegram bot operasional (jadwal, todo, reminder, keuangan, laporan) |
| **Hermes Bot** | Pintu Telegram ke Hermes Agent Runtime (percakapan AI, perencanaan, coding, riset) |
| **Portfolio Web** | Situs publik + dashboard pribadi di `rizkyxibrahim.com` |

## Tech Stack

- PHP 8.5+
- Laravel 13
- PostgreSQL 18
- Laravel Sanctum for API authentication
- REST API with versioned endpoints (`/api/v1/`)
- API Resources for consistent JSON representation
- Laravel Factory for test data generation

## Response Format

### Success — `200 OK`

```json
{
  "status": "success",
  "code": "SCHEDULES_RETRIEVED",
  "message": "Schedules retrieved.",
  "data": { ... },
  "meta": {
    "request_id": "req_...",
    "pagination": { ... }
  }
}
```

### Validation Failed — `422 Unprocessable Content`

```json
{
  "type": "about:blank",
  "title": "Validation failed.",
  "status": 422,
  "detail": "One or more fields contain invalid values.",
  "instance": "/api/v1/schedules",
  "code": "VALIDATION_ERROR",
  "errors": [
    {
      "field": "title",
      "code": "REQUIRED",
      "message": "The title field is required."
    }
  ],
  "request_id": "req_..."
}
```

### Unauthenticated — `401 Unauthorized`

```json
{
  "type": "about:blank",
  "title": "Unauthorized",
  "status": 401,
  "detail": "Authentication is required to access this resource.",
  "instance": "/api/v1/auth/me",
  "code": "AUTHENTICATION_REQUIRED",
  "request_id": "req_..."
}
```

### Not Found — `404 Not Found`

```json
{
  "type": "about:blank",
  "title": "Not Found",
  "status": 404,
  "detail": "The requested resource was not found.",
  "instance": "/api/v1/schedules/999",
  "code": "RESOURCE_NOT_FOUND",
  "request_id": "req_..."
}
```

## Database Schema

All personal tables have `user_id` as foreign key to `users` table with `onDelete('cascade')`.

### Table `users` (extended)

| Column | Type | Description |
|--------|------|-------------|
| id | bigInteger | Primary key |
| name | string | Full name |
| username | string (unique) | Username for login |
| phone | string (unique) | Phone number for login |
| email | string (unique) | Email for login |
| profile_photo_path | string (nullable) | Profile photo path in Storage |
| password | string | Hashed password |
| email_verified_at | timestamp | Email verification timestamp |
| created_at, updated_at | timestamp | Timestamps |

### Table `password_reset_tokens`

| Column | Type | Description |
|--------|------|-------------|
| email | string | Primary key (email) |
| token | string | Reset token |
| created_at | timestamp | Token creation time |

### Table `schedules`

| Column | Type | Description |
|--------|------|-------------|
| id | bigInteger | Primary key |
| user_id | bigInteger | FK to users |
| title | string | Schedule title |
| description | text | Description |
| start_time | datetime | Start time |
| end_time | datetime | End time |
| location | string | Location |
| is_all_day | boolean | All-day event |
| recurring | boolean | Is recurring |
| recurring_pattern | string | daily, weekdays, weekly, monthly |
| timezone | string | Default `Asia/Jakarta` |
| is_overnight | boolean | Overnight event |
| created_at, updated_at | timestamp | Timestamps |

### Table `todos`

| Column | Type | Description |
|--------|------|-------------|
| id | bigInteger | Primary key |
| user_id | bigInteger | FK to users |
| title | string | Todo title |
| description | text | Description |
| is_completed | boolean | Completion status |
| scheduled_start_at | datetime (nullable) | Scheduled start time |
| scheduled_end_at | datetime (nullable) | Scheduled end time |
| due_at | datetime (nullable) | Due date |
| priority | string | low, medium, high |
| created_at, updated_at | timestamp | Timestamps |

### Table `reminders`

| Column | Type | Description |
|--------|------|-------------|
| id | bigInteger | Primary key |
| user_id | bigInteger | FK to users |
| title | string | Reminder title |
| description | text | Description |
| reminder_at | datetime | Reminder time |
| is_sent | boolean | Sent status |
| is_completed | boolean | Completed status |
| recurring | boolean | Is recurring |
| recurring_pattern | string | daily, weekdays, weekly, before_event |
| delivery_status | string | pending, sent, failed, delivered |
| retry_count | integer | Retry count |
| max_retries | integer | Max retries |
| dedupe_key | string (unique) | Deduplication key |
| created_at, updated_at | timestamp | Timestamps |

### Table `finance_transactions`

| Column | Type | Description |
|--------|------|-------------|
| id | bigInteger | Primary key |
| user_id | bigInteger | FK to users |
| type | enum | income, expense |
| amount | decimal(15,2) | Amount |
| category | string | Category |
| description | text | Description |
| transaction_date | date | Transaction date |
| created_at, updated_at | timestamp | Timestamps |

### Table `portfolio_skills`

| Column | Type | Description |
|--------|------|-------------|
| id | bigInteger | Primary key |
| user_id | bigInteger | FK to users |
| name | string | Skill name |
| category | string | Category (programming, design, etc.) |
| level | string | beginner, intermediate, advanced, expert |
| years_of_experience | integer | Years of experience |
| created_at, updated_at | timestamp | Timestamps |

### Table `portfolio_projects`

| Column | Type | Description |
|--------|------|-------------|
| id | bigInteger | Primary key |
| user_id | bigInteger | FK to users |
| title | string | Project title |
| description | text | Description |
| image_url | string | Image URL |
| github_url | string | GitHub repository URL |
| live_url | string | Live/demo URL |
| start_date | date | Start date |
| end_date | date | End date |
| is_featured | boolean | Featured project |
| created_at, updated_at | timestamp | Timestamps |

## Requirements

- PHP version compatible with the selected Laravel release (tested with PHP 8.5.11)
- Composer
- PostgreSQL (tested with PostgreSQL 18 via Laragon)
- Git
- Node.js & npm (for frontend assets, if applicable)

## Installation & Setup

### 1. Clone repository

```bash
git clone https://github.com/rizkyxibrahim/xibra-api.git
cd xibra-api
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Configure environment variables

Copy `.env.example` to `.env` and adjust the values:

```bash
copy .env.example .env
```

### 4. Generate application key

```bash
php artisan key:generate
```

### 5. Configure database

Edit `.env` and set PostgreSQL connection:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=xibra_api
DB_USERNAME=rizkyxibrahim
DB_PASSWORD=your_password_here
```

Create database `xibra_api` in PostgreSQL before running migration, ensure user `rizkyxibrahim` has access to the database.

### 6. Run migrations

```bash
php artisan migrate
```

### 7. Configure storage symlink (for profile photos)

```bash
php artisan storage:link
```

### 8. Start the development server

```bash
php artisan serve
```

Access API at `http://localhost:8000`.

## Environment Variables

| Variable | Example Value | Description |
|----------|---------------|-------------|
| `APP_NAME` | `Xibra API` | Application name |
| `APP_ENV` | `local` | Environment (local, production) |
| `APP_DEBUG` | `true` | Debug mode |
| `APP_URL` | `http://localhost:8000` | Application URL |
| `DB_CONNECTION` | `pgsql` | Database driver |
| `DB_HOST` | `127.0.0.1` | PostgreSQL host |
| `DB_PORT` | `5432` | PostgreSQL port |
| `DB_DATABASE` | `xibra_api` | Database name |
| `DB_USERNAME` | `rizkyxibrahim` | Database username |
| `DB_PASSWORD` | `*` | Database password (do not commit) |
| `SESSION_DRIVER` | `database` | Session driver |
| `QUEUE_CONNECTION` | `database` | Queue driver |
| `CACHE_STORE` | `database` | Cache driver |

⚠️ Never commit tokens, API keys, database credentials, or personal files to Git.

## Authentication (Laravel Sanctum)

API uses Laravel Sanctum for token authentication. Login accepts `identity` (username, phone, or email) and `password`.

### Login

```http
POST /api/v1/auth/login
Content-Type: application/json

{
  "identity": "testuser",
  "password": "password123"
}
```

Response:

```json
{
  "status": "success",
  "code": "LOGIN_SUCCESS",
  "message": "Login successful.",
  "data": {
    "user": { "id": 1, "name": "...", "username": "...", "phone": "...", "email": "..." },
    "token": "1|...",
    "token_type": "Bearer"
  },
  "meta": { "request_id": "...", "pagination": null }
}
```

### Forgot Password

```http
POST /api/v1/auth/forgot-password
Content-Type: application/json

{
  "email": "user@example.com"
}
```

Response:

```json
{
  "status": "success",
  "code": "PASSWORD_RESET_LINK_SENT",
  "message": "Password reset link has been sent to your email.",
  "data": null,
  "meta": { "request_id": "...", "pagination": null }
}
```

### Reset Password

```http
POST /api/v1/auth/reset-password
Content-Type: application/json

{
  "email": "user@example.com",
  "token": "<token_from_email>",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

Response:

```json
{
  "status": "success",
  "code": "PASSWORD_RESET_SUCCESS",
  "message": "Password has been changed successfully. Please log in with your new password.",
  "data": null,
  "meta": { "request_id": "...", "pagination": null }
}
```

### Access Protected Endpoints

Include `Authorization: Bearer <token>` header on every request.

```http
GET /api/v1/auth/me
Authorization: Bearer ***
```

### Logout

```http
POST /api/v1/auth/logout
Authorization: Bearer ***
```

### Upload Profile Photo

```http
POST /api/v1/profile/photo
Authorization: Bearer ***
Content-Type: multipart/form-data

photo: <image file>
```

### Delete Profile Photo

```http
DELETE /api/v1/profile/photo
Authorization: Bearer ***
```

## API Endpoints

### Authentication (Public)

| Endpoint | Method | Description | Auth |
|----------|--------|-------------|------|
| `/api/v1/auth/login` | POST | Login (username/phone/email + password) | No |
| `/api/v1/auth/forgot-password` | POST | Send password reset link | No |
| `/api/v1/auth/reset-password` | POST | Reset password with token | No |
| `/api/v1/auth/logout` | POST | Logout (revoke token) | Yes |
| `/api/v1/auth/me` | GET | Authenticated user profile | Yes |

### Profile Photo (Protected)

| Endpoint | Method | Description | Auth |
|----------|--------|-------------|------|
| `/api/v1/profile/photo` | POST | Upload profile photo | Yes |
| `/api/v1/profile/photo` | DELETE | Delete profile photo | Yes |

### Schedules

| Endpoint | Method | Description | Auth |
|----------|--------|-------------|------|
| `/api/v1/schedules` | GET | List user schedules | Yes |
| `/api/v1/schedules` | POST | Create new schedule | Yes |
| `/api/v1/schedules/{id}` | GET | Schedule detail | Yes |
| `/api/v1/schedules/{id}` | PUT | Update schedule | Yes |
| `/api/v1/schedules/{id}` | DELETE | Delete schedule | Yes |

### Todos

| Endpoint | Method | Description | Auth |
|----------|--------|-------------|------|
| `/api/v1/todos` | GET | List user todos | Yes |
| `/api/v1/todos` | POST | Create new todo | Yes |
| `/api/v1/todos/{id}` | GET | Todo detail | Yes |
| `/api/v1/todos/{id}` | PUT | Update todo | Yes |
| `/api/v1/todos/{id}` | DELETE | Delete todo | Yes |

### Reminders

| Endpoint | Method | Description | Auth |
|----------|--------|-------------|------|
| `/api/v1/reminders` | GET | List user reminders | Yes |
| `/api/v1/reminders` | POST | Create new reminder | Yes |
| `/api/v1/reminders/{id}` | GET | Reminder detail | Yes |
| `/api/v1/reminders/{id}` | PUT | Update reminder | Yes |
| `/api/v1/reminders/{id}` | DELETE | Delete reminder | Yes |

### Finance Transactions

| Endpoint | Method | Description | Auth |
|----------|--------|-------------|------|
| `/api/v1/finance/transactions` | GET | List financial transactions | Yes |
| `/api/v1/finance/transactions` | POST | Create new transaction | Yes |
| `/api/v1/finance/transactions/{id}` | GET | Transaction detail | Yes |
| `/api/v1/finance/transactions/{id}` | PUT | Update transaction | Yes |
| `/api/v1/finance/transactions/{id}` | DELETE | Delete transaction | Yes |

**Note:** The `type` field accepts: `in`, `out`, `income`, `expense`. API normalizes `in` → `income` and `out` → `expense`.

Filter: `?type=income|expense|all`

### Portfolio Skills

| Endpoint | Method | Description | Auth |
|----------|--------|-------------|------|
| `/api/v1/portfolio/skills` | GET | List skills | Yes |
| `/api/v1/portfolio/skills` | POST | Add skill | Yes |
| `/api/v1/portfolio/skills/{id}` | GET | Skill detail | Yes |
| `/api/v1/portfolio/skills/{id}` | PUT | Update skill | Yes |
| `/api/v1/portfolio/skills/{id}` | DELETE | Delete skill | Yes |

### Portfolio Projects

| Endpoint | Method | Description | Auth |
|----------|--------|-------------|------|
| `/api/v1/portfolio/projects` | GET | List projects | Yes |
| `/api/v1/portfolio/projects` | POST | Create project | Yes |
| `/api/v1/portfolio/projects/{id}` | GET | Project detail | Yes |
| `/api/v1/portfolio/projects/{id}` | PUT | Update project | Yes |
| `/api/v1/portfolio/projects/{id}` | DELETE | Delete project | Yes |

### Public Portfolio (No Login)

| Endpoint | Method | Description | Auth |
|----------|--------|-------------|------|
| `/api/v1/public/profile` | GET | Public profile | No |
| `/api/v1/public/skills` | GET | Public skills | No |
| `/api/v1/public/projects` | GET | Public projects | No |
| `/api/v1/public/contact` | GET | Contact endpoint | No |

## Testing

```bash
php artisan test
```

All features tested with PHPUnit using RefreshDatabase and Model Factories.

## Postman Collection

Import the collection from `docs/xibra-api-postman-collection.json` for complete API testing with examples.

## Git Workflow

1. Read `README.md` and project status before changing code.
2. Work on one small feature in VS Code.
3. Test the feature.
4. Update `README.md` if behavior or setup changes.
5. Commit and push with clear message:

```
[Initialize] Set up Laravel API project
[Feature] Add schedules, todos, reminders, finance, portfolio models and migrations
[Feature] Add Sanctum authentication and API controllers
[Feature] Add scheduling, recurring, delivery status, and profile photo support
[Fix] Fix database connection config
[Feature] Add forgot/reset password functionality
[Fix] Normalize finance transaction types (in/out → income/expense)
[Fix] Fix schedule recurring validation (nullable boolean, default false)
```

### Example commit

```bash
git add .
git commit -m "[Feature] Add forgot/reset password functionality"
git push origin main
```

## Deployment & Recovery

Not yet implemented. Will be added when ready to deploy to server (`xibra-infra`).

Guide will include:
- Deploy to 24/7 server (VPS)
- Reverse proxy configuration (Nginx)
- PostgreSQL database backup
- Recovery from backup

## Project Status & Next Steps

**Status:** Implemented — Laravel 13 installed, Sanctum configured, PostgreSQL connected, all migrations applied, controllers and routes implemented with Form Requests and API Resources.

### Next Steps

1. Implement Form Request validation for each controller (done).
2. Implement API Resources for structured data representation (done).
3. Implement Policies for row-level authorization.
4. Connect with `xibra-bot` (grammY + TypeScript) via API endpoints.
5. Connect with `hermes-config` for AI agent integration.
6. Setup `portfolio-web` (Nuxt + TypeScript + Tailwind) for frontend.
7. Deploy to Supabase (PostgreSQL) and Cloudflare Workers.

---

**Repository:** `xibra-api`  
**Description:** Central REST API for Xibra Platform, powering personal productivity, scheduling, finance, and AI integrations.  
**License:** To be decided before public distribution.
