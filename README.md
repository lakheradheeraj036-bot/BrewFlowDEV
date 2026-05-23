# BrewFlow — Super Admin Panel

A production-grade SaaS Super Admin system built with **Laravel 12**, **Livewire 4**, **Tailwind CSS 4**, and **Alpine.js**.

---

## 🚀 Quick Start

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

Ensure your `.env` has the MySQL connection:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=brewflow
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Migrate & Seed
```bash
php artisan migrate:fresh --seed
```

### 4. Build Assets
```bash
npm run build
# or for development:
npm run dev
```

### 5. Start the Server
```bash
php artisan serve
```

Visit: **http://localhost:8000/super-admin/login**

---

## 🔐 Default Credentials

| Role | Email | Password |
|------|-------|----------|
| Super Admin | `admin@brewflow.com` | `password` |
| Business Owner | `owner@brewflow.com` | `password` |

---

## 📁 Project Architecture

```
app/
├── Http/
│   ├── Controllers/
│   │   └── SuperAdmin/
│   │       ├── BusinessController.php        # Business CRUD (index, create, store, edit, update, destroy)
│   │       ├── RolesPermissionsController.php # Role & permission management
│   │       └── SettingsController.php         # Platform settings management
│   ├── Middleware/
│   │   ├── SuperAdminMiddleware.php   # Role guard for super-admin routes
│   │   └── UpdateLastLogin.php       # Stamps last_login_at on auth'd requests
│   └── Requests/
│       └── SuperAdmin/
│           ├── StoreBusinessRequest.php    # Validation for creating business
│           └── UpdateBusinessRequest.php   # Validation for updating business
├── Livewire/
│   ├── Auth/
│   │   ├── Login.php                 # Rate-limited super admin login
│   │   └── Logout.php                # Session-destroying logout
│   └── SuperAdmin/
│       ├── Dashboard/
│       │   └── DashboardPage.php     # Stats, recent businesses, quick actions
│       ├── Staff/
│       │   └── StaffManagement.php   # Staff CRUD with role assignment
│       ├── Roles/
│       │   └── RolesPermissions.php  # Interactive permission matrix
│       └── Settings/
│           └── PlatformSettings.php  # Tabbed platform configuration
├── Models/
│   ├── User.php                      # HasRoles, SoftDeletes, business relationship
│   ├── Business.php                  # HasFactory, SoftDeletes, status accessor
│   └── PlatformSetting.php          # Key-value store with typed get/set helpers

resources/views/
├── components/
│   ├── layouts/
│   │   ├── super-admin.blade.php    # Main admin layout (sidebar + header)
│   │   └── auth.blade.php          # Minimal auth layout
│   ├── super-admin/
│   │   ├── sidebar.blade.php       # Collapsible desktop + mobile sidebar
│   │   ├── header.blade.php        # Sticky topbar with search + user menu
│   │   ├── nav-item.blade.php      # Active-aware navigation link
│   │   └── breadcrumb.blade.php    # Hierarchical breadcrumb trail
│   └── ui/
│       ├── stats-card.blade.php    # KPI metric card with trend indicator
│       ├── card.blade.php          # Content panel with optional header/action
│       ├── badge.blade.php         # Status pill with colored dot
│       ├── button.blade.php        # Polymorphic button/link component
│       ├── input.blade.php         # Text input with label/error/hint/prefix
│       ├── select.blade.php        # Styled select with chevron
│       ├── table.blade.php         # Responsive table wrapper
│       ├── modal.blade.php         # Livewire-entangled modal overlay
│       └── empty-state.blade.php   # Zero-data placeholder
├── super-admin/
│   └── businesses/
│       ├── index.blade.php         # Business listing with filters and pagination
│       ├── create.blade.php        # Business creation form
│       └── edit.blade.php          # Business edit form
└── livewire/
    ├── auth/
    │   └── login.blade.php
    └── super-admin/
        ├── dashboard/dashboard-page.blade.php
        ├── staff/staff-management.blade.php
        ├── roles/roles-permissions.blade.php
        └── settings/platform-settings.blade.php

database/
├── migrations/                       # 7 migrations including Spatie tables
└── seeders/
    ├── RolesPermissionsSeeder.php    # 30 permissions across 5 roles
    ├── SuperAdminSeeder.php          # Default admin + owner accounts
    └── BusinessSeeder.php            # 6 sample Australian businesses
```

---

## 🛣️ Routes

| Method | URL | Name | Controller/Component |
|--------|-----|------|-----------|
| GET | `/` | — | → redirect to dashboard |
| GET | `/super-admin/login` | `super-admin.login` | `Auth\Login` |
| POST | `/super-admin/logout` | `super-admin.logout` | `Auth\Logout` |
| GET | `/super-admin/dashboard` | `super-admin.dashboard` | `Dashboard\DashboardPage` |
| GET | `/super-admin/businesses` | `super-admin.businesses.index` | `BusinessController@index` |
| GET | `/super-admin/businesses/create` | `super-admin.businesses.create` | `BusinessController@create` |
| POST | `/super-admin/businesses` | `super-admin.businesses.store` | `BusinessController@store` |
| GET | `/super-admin/businesses/{business}/edit` | `super-admin.businesses.edit` | `BusinessController@edit` |
| PUT | `/super-admin/businesses/{business}` | `super-admin.businesses.update` | `BusinessController@update` |
| POST | `/super-admin/businesses/bulk-status` | `super-admin.businesses.bulk-status` | `BusinessController@bulkUpdateStatus` |
| DELETE | `/super-admin/businesses/delete` | `super-admin.businesses.destroy` | `BusinessController@destroy` |
| GET | `/super-admin/staff` | `super-admin.staff.index` | `Staff\StaffManagement` |
| GET | `/super-admin/roles` | `super-admin.roles.index` | `Roles\RolesPermissions` |
| GET | `/super-admin/settings` | `super-admin.settings.index` | `Settings\PlatformSettings` |

---

## 🎭 Roles & Permissions

| Role | Permissions |
|------|-------------|
| `super_admin` | All 30 permissions |
| `business_owner` | Dashboard, staff CRUD, menu CRUD, orders, reports |
| `manager` | Dashboard, staff view/create/edit, menu CRUD, orders, report view |
| `staff` | Dashboard, orders, menu view |
| `kitchen_staff` | Dashboard, order view/edit, menu view |

---

## 🧩 Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 12 |
| Reactivity | Livewire 4 + Alpine.js |
| Styling | Tailwind CSS 4 via `@tailwindcss/vite` |
| Build | Vite 7 |
| Auth | Custom Livewire login (rate-limited, role-guarded) |
| Permissions | Spatie Laravel Permission v6 |
| Database | MySQL |

---

## 🔧 Development

```bash
# Run all services concurrently (server + queue + logs + vite)
composer run dev

# Run tests
composer run test

# Rebuild from scratch
php artisan migrate:fresh --seed && npm run build
```
