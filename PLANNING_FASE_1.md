# Fase 1 — Fondasi Xibra Platform

## Tujuan
Menyelesaikan `portfolio-api` dan `portfolio-web`, termasuk autentikasi, struktur data, dokumentasi API, pengelolaan portfolio, dan dashboard admin. Hasil akhir: fondasi backend dan website yang stabil, siap untuk dikembangkan ke fase berikutnya.

## Cakupan Lingkup (In Scope)
| Modul | Detail |
|-------|--------|
| Backend API | Laravel 13 + PostgreSQL + Laravel Sanctum |
| Model & Migrasi | Profile, Experience, Education, Skill, Certification, Project, Post, Contact |
| API Resource | Respons terstruktur untuk setiap entitas |
| Autentikasi Admin | Login / Logout / Me via Sanctum token |
| Endpoint Publik | Baca data portfolio (profile, project, skill, experience, education, certification, post) |
| Endpoint Publik | Contact form (tulis pesan kontak) |
| Endpoint Admin | CRUD penuh semua konten portfolio |
| Seeder | Data awal: admin user + contoh data portfolio |
| Dokumentasi | Daftar endpoint API (bisa diperkaya dengan Swagger/OpenAPI nanti) |

## Di Luar Lingkup (Out of Scope) Fase 1
- Todo, reminder, schedule, finance (Fase 2 — Productivity Engine)
- Integrasi Telegram dan Hermes (Fase 3 — Xibra AI Assistant)
- Aplikasi Android (Fase 5 — Ekspansi)
- Cloudflare D1 sebagai database utama (D1 hanya untuk komponen serverless terpilih jika ada kebutuhan)

## Kriteria Keberhasilan (Success Criteria)
- [ ] Backend Laravel berjalan secara lokal dengan PostgreSQL
- [ ] Admin dapat login via Sanctum dan mengelola konten portfolio
- [ ] Endpoint publik mengembalikan data portfolio dengan benar
- [ ] Contact form dapat menerima pesan dan menyimpannya ke database
- [ ] Semua migrasi dapat dijalankan (`php artisan migrate`) dan seed (`php artisan db:seed`)
- [ ] Dokumentasi endpoint tersedia

## Estimasi Waktu
| Tugas | Estimasi |
|-------|----------|
| Setup & konfigurasi Laravel + PostgreSQL | 1–2 hari |
| Model, migrasi, seeder | 2–3 hari |
| Controller, resource, route | 3–4 hari |
| Autentikasi & middleware Sanctum | 1 hari |
| Pengujian API (Postman / Insomnia / curl) | 1–2 hari |
| Dokumentasi endpoint | 1 hari |
| **Total** | **sekitar 1–2 minggu** |

## Risiko & Mitigasi
| Risiko | Mitigasi |
|--------|----------|
| Laravel 13 membutuhkan PHP 8.4+ | Pastikan environment PHP sudah 8.4 sebelum install |
| PostgreSQL di Laragon/Windows | Aktifkan extension `pdo_pgsql` dan `pgsql` di php.ini |
| CORS antara Nuxt (localhost:3000) dan Laravel (localhost:8000) | Konfigurasi `config/cors.php` atau middleware untuk mengizinkan origin frontend |
| Data admin hilang saat reset DB | Gunakan seeder untuk admin user dan data contoh |

## Langkah Selanjutnya
1. Inisialisasi proyek Laravel di `D:/Projects/xibra-api`
2. Konfigurasi database PostgreSQL di `.env`
3. Jalankan migrasi dan seeder
4. Implementasikan endpoint satu per satu sesuai struktur di folder ini