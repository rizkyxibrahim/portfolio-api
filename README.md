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

### Sukses — `200 OK`

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

### Validasi gagal — `422 Unprocessable Content`

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

### Tidak terautentikasi — `401 Unauthorized`

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

### Data tidak ditemukan — `404 Not Found`

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

Semua tabel personal memiliki `user_id` sebagai foreign key ke tabel `users` dengan `onDelete('cascade')`.

### Tabel `users` (diperluas)

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigInteger | Primary key |
| name | string | Nama lengkap |
| username | string (unique) | Username untuk login |
| phone | string (unique) | Nomor telepon untuk login |
| email | string (unique) | Email untuk login |
| profile_photo_path | string (nullable) | Path foto profil di Storage |
| password | string | Hashed password |
| email_verified_at | timestamp | Waktu verifikasi email |
| created_at, updated_at | timestamp | Timestamps |

### Tabel `schedules`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigInteger | Primary key |
| user_id | bigInteger | FK ke users |
| title | string | Judul jadwal |
| description | text | Deskripsi |
| start_time | datetime | Waktu mulai |
| end_time | datetime | Waktu selesai |
| location | string | Lokasi |
| is_all_day | boolean | Apakah sehari penuh |
| recurring | boolean | Apakah berulang |
| recurring_pattern | string | daily, weekdays, weekly, monthly |
| timezone | string | Default `Asia/Jakarta` |
| is_overnight | boolean | Apakah melewati tengah malam |
| created_at, updated_at | timestamp | Timestamps |

### Tabel `todos`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigInteger | Primary key |
| user_id | bigInteger | FK ke users |
| title | string | Judul tugas |
| description | text | Deskripsi |
| is_completed | boolean | Status selesai |
| scheduled_start_at | datetime (nullable) | Waktu terjadwal mulai |
| scheduled_end_at | datetime (nullable) | Waktu terjadwal selesai |
| due_at | datetime (nullable) | Tenggat waktu |
| priority | string | low, medium, high |
| created_at, updated_at | timestamp | Timestamps |

### Tabel `reminders`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigInteger | Primary key |
| user_id | bigInteger | FK ke users |
| title | string | Judul pengingat |
| description | text | Deskripsi |
| reminder_at | datetime | Waktu pengingat |
| is_sent | boolean | Apakah sudah terkirim |
| is_completed | boolean | Apakah sudah selesai |
| recurring | boolean | Apakah berulang |
| recurring_pattern | string | daily, weekdays, weekly, before_event |
| delivery_status | string | pending, sent, failed, delivered |
| retry_count | integer | Jumlah percobaan ulang |
| max_retries | integer | Maksimal percobaan ulang |
| dedupe_key | string (unique) | Kunci pencegahan duplikasi |
| created_at, updated_at | timestamp | Timestamps |

### Tabel `finance_transactions`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigInteger | Primary key |
| user_id | bigInteger | FK ke users |
| type | enum | income, expense |
| amount | decimal(15,2) | Jumlah uang |
| category | string | Kategori |
| description | text | Deskripsi |
| transaction_date | date | Tanggal transaksi |
| created_at, updated_at | timestamp | Timestamps |

### Tabel `portfolio_skills`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigInteger | Primary key |
| user_id | bigInteger | FK ke users |
| name | string | Nama keterampilan |
| category | string | Kategori (programming, design, dll.) |
| level | string | beginner, intermediate, advanced, expert |
| years_of_experience | integer | Tahun pengalaman |
| created_at, updated_at | timestamp | Timestamps |

### Tabel `portfolio_projects`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigInteger | Primary key |
| user_id | bigInteger | FK ke users |
| title | string | Judul proyek |
| description | text | Deskripsi |
| image_url | string | URL gambar |
| github_url | string | URL repositori GitHub |
| live_url | string | URL live/demo |
| start_date | date | Tanggal mulai |
| end_date | date | Tanggal selesai |
| is_featured | boolean | Apakah unggulan |
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

Buat database `xibra_api` di PostgreSQL sebelum menjalankan migration, pastikan user `rizkyxibrahim` memiliki akses ke database tersebut.

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

Akses API di `http://localhost:8000`.

## Environment Variables

| Variabel | Contoh Nilai | Keterangan |
|----------|-------------|------------|
| `APP_NAME` | `Xibra API` | Nama aplikasi |
| `APP_ENV` | `local` | Lingkungan (local, production) |
| `APP_DEBUG` | `true` | Mode debug |
| `APP_URL` | `http://localhost:8000` | URL aplikasi |
| `DB_CONNECTION` | `pgsql` | Driver database |
| `DB_HOST` | `127.0.0.1` | Host PostgreSQL |
| `DB_PORT` | `5432` | Port PostgreSQL |
| `DB_DATABASE` | `xibra_api` | Nama database |
| `DB_USERNAME` | `rizkyxibrahim` | Username database |
| `DB_PASSWORD` | `*` | Password database (jangan commit) |
| `SESSION_DRIVER` | `database` | Driver session |
| `QUEUE_CONNECTION` | `database` | Driver queue |
| `CACHE_STORE` | `database` | Driver cache |

⚠️ Jangan pernah commit token, API key, database credentials, atau file pribadi ke Git.

## Authentication (Laravel Sanctum)

API menggunakan Laravel Sanctum untuk autentikasi token. Login menerima `identity` (username, phone, atau email) dan `password`.

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

### Mengakses endpoint terlindungi

Sertakan header `Authorization: Bearer <token>` pada setiap request.

```http
GET /api/v1/auth/me
Authorization: Bearer 1|...
```

### Logout

```http
POST /api/v1/auth/logout
Authorization: Bearer 1|...
```

### Upload Foto Profil

```http
POST /api/v1/profile/photo
Authorization: Bearer 1|...
Content-Type: multipart/form-data

photo: <file image>
```

### Hapus Foto Profil

```http
DELETE /api/v1/profile/photo
Authorization: Bearer 1|...
```

## API Endpoints

### Authentication (Public)

| Endpoint | Method | Deskripsi | Auth |
|----------|--------|-----------|------|
| `/api/v1/auth/login` | POST | Login (username/phone/email + password) | Tidak |
| `/api/v1/auth/logout` | POST | Logout (revoke token) | Ya |
| `/api/v1/auth/me` | GET | Profil pengguna terautentikasi | Ya |

### Profile Photo (Protected)

| Endpoint | Method | Deskripsi | Auth |
|----------|--------|-----------|------|
| `/api/v1/profile/photo` | POST | Upload foto profil | Ya |
| `/api/v1/profile/photo` | DELETE | Hapus foto profil | Ya |

### Schedules (Jadwal)

| Endpoint | Method | Deskripsi | Auth |
|----------|--------|-----------|------|
| `/api/v1/schedules` | GET | Daftar jadwal pengguna | Ya |
| `/api/v1/schedules` | POST | Buat jadwal baru | Ya |
| `/api/v1/schedules/{id}` | GET | Detail jadwal | Ya |
| `/api/v1/schedules/{id}` | PUT | Update jadwal | Ya |
| `/api/v1/schedules/{id}` | DELETE | Hapus jadwal | Ya |

### Todos (Tugas)

| Endpoint | Method | Deskripsi | Auth |
|----------|--------|-----------|------|
| `/api/v1/todos` | GET | Daftar tugas pengguna | Ya |
| `/api/v1/todos` | POST | Buat tugas baru | Ya |
| `/api/v1/todos/{id}` | GET | Detail tugas | Ya |
| `/api/v1/todos/{id}` | PUT | Update tugas | Ya |
| `/api/v1/todos/{id}` | DELETE | Hapus tugas | Ya |

### Reminders (Pengingat)

| Endpoint | Method | Deskripsi | Auth |
|----------|--------|-----------|------|
| `/api/v1/reminders` | GET | Daftar pengingat pengguna | Ya |
| `/api/v1/reminders` | POST | Buat pengingat baru | Ya |
| `/api/v1/reminders/{id}` | GET | Detail pengingat | Ya |
| `/api/v1/reminders/{id}` | PUT | Update pengingat | Ya |
| `/api/v1/reminders/{id}` | DELETE | Hapus pengingat | Ya |

### Finance Transactions (Keuangan)

| Endpoint | Method | Deskripsi | Auth |
|----------|--------|-----------|------|
| `/api/v1/finance/transactions` | GET | Daftar transaksi keuangan | Ya |
| `/api/v1/finance/transactions` | POST | Buat transaksi baru | Ya |
| `/api/v1/finance/transactions/{id}` | GET | Detail transaksi | Ya |
| `/api/v1/finance/transactions/{id}` | PUT | Update transaksi | Ya |
| `/api/v1/finance/transactions/{id}` | DELETE | Hapus transaksi | Ya |

### Portfolio Skills (Keterampilan)

| Endpoint | Method | Deskripsi | Auth |
|----------|--------|-----------|------|
| `/api/v1/portfolio/skills` | GET | Daftar keterampilan | Ya |
| `/api/v1/portfolio/skills` | POST | Tambah keterampilan | Ya |
| `/api/v1/portfolio/skills/{id}` | GET | Detail keterampilan | Ya |
| `/api/v1/portfolio/skills/{id}` | PUT | Update keterampilan | Ya |
| `/api/v1/portfolio/skills/{id}` | DELETE | Hapus keterampilan | Ya |

### Portfolio Projects (Proyek)

| Endpoint | Method | Deskripsi | Auth |
|----------|--------|-----------|------|
| `/api/v1/portfolio/projects` | GET | Daftar proyek | Ya |
| `/api/v1/portfolio/projects` | POST | Buat proyek baru | Ya |
| `/api/v1/portfolio/projects/{id}` | GET | Detail proyek | Ya |
| `/api/v1/portfolio/projects/{id}` | PUT | Update proyek | Ya |
| `/api/v1/portfolio/projects/{id}` | DELETE | Hapus proyek | Ya |

### Public Portfolio (Tanpa Login)

| Endpoint | Method | Deskripsi | Auth |
|----------|--------|-----------|------|
| `/api/v1/public/profile` | GET | Profil publik | Tidak |
| `/api/v1/public/skills` | GET | Keterampilan publik | Tidak |
| `/api/v1/public/projects` | GET | Proyek publik | Tidak |
| `/api/v1/public/contact` | GET | Endpoint kontak | Tidak |

## Testing

```bash
php artisan test
```

Semua fitur telah diuji dengan PHPUnit menggunakan RefreshDatabase dan Model Factories.

## Git Workflow

1. Baca `README.md` dan status proyek sebelum mengubah kode.
2. Kerjakan satu fitur kecil di VS Code.
3. Uji fitur tersebut.
4. Perbarui `README.md` jika perilaku atau setup berubah.
5. Commit dan push dengan pesan yang jelas:

```
[Initialize] Set up Laravel API project
[Feature] Add schedules, todos, reminders, finance, portfolio models and migrations
[Feature] Add Sanctum authentication and API controllers
[Feature] Add scheduling, recurring, delivery status, and profile photo support
[Fix] Fix database connection config
```

### Contoh commit

```bash
git add .
git commit -m "[Feature] Add Sanctum authentication and API controllers"
git push origin main
```

## Deployment & Recovery

Belum diimplementasikan. Akan ditambahkan saat siap deploy ke server (`xibra-infra`).

Panduan akan mencakup:
- Deploy ke server 24/7 (VPS)
- Konfigurasi reverse proxy (Nginx)
- Backup database PostgreSQL
- Pemulihan dari backup

## Project Status & Next Steps

**Status:** Implemented — Laravel 13 installed, Sanctum configured, PostgreSQL connected, all migrations applied, controllers and routes implemented with Form Requests and API Resources.

### Next Steps

1. Implementasi Form Request validation terpisah untuk setiap controller (sudah dilakukan).
2. Implementasi API Resources untuk representasi data yang lebih terstruktur (sudah dilakukan).
3. Implementasi Policies untuk otorisasi tingkat baris.
4. Hubungkan dengan `xibra-bot` (grammY + TypeScript) melalui endpoint API.
5. Hubungkan dengan `hermes-config` untuk integrasi agent AI.
6. Setup `portfolio-web` (Nuxt + TypeScript + Tailwind) untuk frontend.

---

**Repository:** `xibra-api`
**Description:** Central REST API for Xibra Platform, powering personal productivity, scheduling, finance, and AI integrations.
**License:** To be decided before public distribution.
