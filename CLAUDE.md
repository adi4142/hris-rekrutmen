# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Laravel 7.x HRIS Recruitment System built with PHP 7.2.5. Manages job recruitment workflow from applicant registration through hiring process with multi-role access control.

## Development Commands

```bash
# Install dependencies
composer install

# Run migrations
php artisan migrate

# Run tests
php artisan test
php artisan test --filter=AdminDashboardTest  # Run specific test

# Development server (Laragon environment)
# Access via http://localhost/sistem-hris-rekrutmen

# Common artisan commands
php artisan route:list
php artisan db:seed
php artisan make:controller ControllerName
php artisan make:model ModelName -m
```

## Architecture Overview

### Role-Based Access Control

The system uses a role-based middleware architecture with 4 distinct user roles:

| Role | Dashboard Route | Access Level |
|------|-----------------|--------------|
| Super Admin | `/superadmin/dashboard` | User management, activity logs |
| Admin | `/admin/dashboard` | Full system access (roles, users, departments, positions, selections, jobs) |
| HRD | `/hrd/dashboard` | Operational recruitment (same resources as Admin) |
| Pelamar/Tamu | `/applicant/dashboard` | Application submission, profile, track status |

**Key Middleware:**
- `CheckRole` (app/Http/Middleware/CheckRole.php): Validates user role against allowed roles
- `EnsureRoleVerified` (app/Http/Middleware/EnsureRoleVerified.php): Admin/HRD must verify license code before accessing dashboard

### Core Domain Models

**Recruitment Flow:**
```
JobApplicant (pelamar) → JobApplication (lamaran per lowongan) → RecruitmentBatch → SelectionApplicant (nilai per tahap)
```

**Key Models:**
- `User` - System users with role assignment (primary key: `user_id`)
- `JobApplicant` - Applicant profile data (CV, documents, personal info)
- `JobApplication` - Application linking applicant to vacancy with status tracking
- `JobVacancie` - Job posting with department, position, quotas
- `RecruitmentBatch` - Grouping of applicants for a vacancy with selection stages
- `SelectionApplicant` - Score tracking per selection stage per applicant
- `Selection` - Master selection stages (e.g., "Interview", "Written Test")

### Key Conventions

- All tables use custom primary keys (`user_id`, `job_applicant_id`, `application_id`, etc.) - NOT default `id`
- Soft deletes enabled on `JobApplicant`, `JobApplication`, `SelectionApplicant`
- Status transitions managed in `JobApplication` model with badge/status helpers

### Authentication Flow

1. Public users can browse jobs (`/lowongan`) and apply via `/jobapplicant/create`
2. Login at `/login` redirects to role-specific dashboard
3. Admin/HRD must verify license code on first login (`PasswordChangeController`)
4. Password reset available at `/forgot-password`

### File Uploads

Documents stored in `storage/app/public/`:
- Applicant documents: CV, portfolio, certificates, diploma, transcript
- Route fallback defined for `/storage/{path}` if symlink missing

### External Integrations

- **Email**: SMTP via Gmail (config in `.env`)
- **Gemini AI**: API integration for assistant features (`.env: GEMINI_API_KEY`)
- **PDF**: barryvdh/laravel-dompdf for generating offer letters

### Testing

- PHPUnit configured for SQLite in-memory testing
- Test example: `tests/Feature/AdminDashboardTest.php`
- Uses `RefreshDatabase` trait for isolated test runs
