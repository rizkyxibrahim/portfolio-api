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
- REST API with versioned endpoints (`/api/v1`)

## Planned Features

- Authentication and authorization (Laravel Sanctum)
- User profile and preferences
- Shift schedule management
- Todo and task management
- Reminder management
- Income and expense tracking
- Budgets and financial reports
- Integration endpoints for Telegram bots and AI tools

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

### 7. Start the development server

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

## Run Locally

### Menjalankan

```bash
php artisan serve
```

### Menghentikan

Tekan `Ctrl+C` di terminal.

### Memeriksa aplikasi

Buka `http://localhost:8000/api/v1` atau endpoint yang tersedia. Untuk dokumentasi lengkap endpoint, lihat section **API / commands** di bawah ini.

## API / Commands

### API Conventions

- API version prefix: `/api/v1`
- JSON request and response bodies
- Consistent response structure and HTTP status codes
- Validation and authorization on the server
- Automated tests for important business rules

### Endpoint yang tersedia (saat inisialisasi)

Laravel bawaan menyediakan endpoint autentikasi dan routing dasar. Endpoint bisnis Xibra (jadwal, todo, keuangan, laporan) akan ditambahkan secara bertahap sesuai roadmap.

| Endpoint | Method | Deskripsi | Status |
|----------|--------|-----------|--------|
| `/api/v1` | GET | Info API | ✅ Siap |
| `/api/v1/auth/login` | POST | Login | 🔲 Direncanakan |
| `/api/v1/auth/register` | POST | Register | 🔲 Direncanakan |
| `/api/v1/schedules` | GET/POST | Manajemen jadwal | 🔲 Direncanakan |
| `/api/v1/todos` | GET/POST | Manajemen tugas | 🔲 Direncanakan |
| `/api/v1/reminders` | GET/POST | Manajemen pengingat | 🔲 Direncanakan |
| `/api/v1/finance` | GET/POST | Pencatatan keuangan | 🔲 Direncanakan |
| `/api/v1/reports` | GET | Laporan keuangan | 🔲 Direncanakan |

## Testing & Troubleshooting

### Menjalankan tes

```bash
php artisan test
```

### Masalah umum

| Masalah | Penyebab | Solusi |
|---------|----------|--------|
| Koneksi database gagal | PostgreSQL belum mati atau kredensial salah | Pastikan PostgreSQL berjalan via Laragon dan `.env` sudah benar |
| `APP_KEY` belum di-set | Key tidak di-generate | Jalankan `php artisan key:generate` |
| Migration error | Tabel sudah ada atau database belum dibuat | Buat database `xibra_api` terlebih dahulu, atau reset dengan `php artisan migrate:fresh` |

## Git Workflow

1. Baca `README.md` dan status proyek sebelum mengubah kode.
2. Kerjakan satu fitur kecil di VS Code.
3. Uji fitur tersebut.
4. Perbarui `README.md` jika perilaku atau setup berubah.
5. Commit dan push dengan pesan yang jelas:

```
[Initialize] Set up Laravel API project
[Feature] Add schedule endpoints
[Fix] Fix database connection config
```

### Contoh commit

```bash
git add .
git commit -m "[Initialize] Set up Laravel API project"
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

**Status:** Initialized — Laravel 13 installed, PostgreSQL connected, migrations applied.

### Next Steps

1. Buat repository GitHub `xibra-api` dan push kode ini.
2. Implementasi autentikasi dengan Laravel Sanctum.
3. Buat migration dan model untuk jadwal, todo, reminder, dan keuangan.
4. Buat endpoint REST API untuk masing-masing modul.
5. Hubungkan dengan `xibra-bot` dan `hermes-config` melalui tools/API.

---

**Repository:** `xibra-api`
**Description:** Central REST API for Xibra Platform, powering personal productivity, scheduling, finance, and AI integrations.
**License:** To be decided before public distribution.
