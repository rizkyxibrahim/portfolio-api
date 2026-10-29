# Struktur Proyek Xibra API (Fase 1)

```
xibra-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       ├── AuthController.php
│   │   │       ├── ProfileController.php
│   │   │       ├── ProjectController.php
│   │   │       ├── SkillController.php
│   │   │       ├── ExperienceController.php
│   │   │       ├── EducationController.php
│   │   │       ├── CertificationController.php
│   │   │       ├── PostController.php
│   │   │       └── ContactController.php
│   │   ├── Resources/
│   │   │   ├── ProfileResource.php
│   │   │   ├── ProjectResource.php
│   │   │   ├── SkillResource.php
│   │   │   ├── ExperienceResource.php
│   │   │   ├── EducationResource.php
│   │   │   ├── CertificationResource.php
│   │   │   ├── PostResource.php
│   │   │   └── ContactResource.php
│   │   └── Middleware/
│   │       └── EnsureAdminRole.php (opsional)
│   ├── Models/
│   │   ├── Profile.php
│   │   ├── Experience.php
│   │   ├── Education.php
│   │   ├── Skill.php
│   │   ├── Certification.php
│   │   ├── Project.php
│   │   ├── Post.php
│   │   └── Contact.php
│   └── ...
├── database/
│   ├── migrations/
│   │   ├── create_profiles_table.php
│   │   ├── create_experiences_table.php
│   │   ├── create_educations_table.php
│   │   ├── create_skills_table.php
│   │   ├── create_certifications_table.php
│   │   ├── create_projects_table.php
│   │   ├── create_posts_table.php
│   │   └── create_contacts_table.php
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   ├── AdminUserSeeder.php
│   │   └── PortfolioSeeder.php
│   └── factories/
├── routes/
│   └── api.php
├── config/
│   ├── cors.php
│   └── sanctum.php
├── .env
├── .env.example
├── composer.json
└── artisan
```

## Hubungan Antar Model

```
User (1) ──┬── Profile (1)
           └── (Sanctum tokens)

Profile (1) ──┬── Experience (*)
              ├── Education (*)
              ├── Skill (*)
              ├── Certification (*)
              ├── Project (*)
              └── Post (*)

Project (1) ──┬── Post (*) (studi kasus terkait, opsional)
```

## Endpoint Ringkasan

| Method | Endpoint | Akses | Keterangan |
|--------|----------|-------|------------|
| POST | /api/v1/login | Publik | Login admin, dapatkan Sanctum token |
| POST | /api/v1/logout | Auth | Logout, hapus token aktif |
| GET | /api/v1/me | Auth | Data user yang login |
| GET | /api/v1/profile | Publik | Profil publik |
| GET/PUT | /api/v1/admin/profile | Auth | Pengelolaan profil admin |
| GET | /api/v1/projects | Publik | Daftar proyek |
| GET | /api/v1/projects/{slug} | Publik | Detail proyek |
| POST/PUT/DELETE | /api/v1/admin/projects | Auth | CRUD proyek admin |
| GET | /api/v1/skills | Publik | Daftar skill |
| ... | ... | ... | ... |
| POST | /api/v1/contact | Publik | Kirim pesan kontak |
| GET | /api/v1/admin/contacts | Auth | Daftar pesan masuk admin |