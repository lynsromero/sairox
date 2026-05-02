# Sairox CMS

Sairox is a modern, lightweight CMS built with Laravel and Filament as a WordPress alternative. It provides a clean admin interface and extensible architecture for building content-driven websites.

## Tech Stack

| Category    | Technology    |
| ----------- | ------------- |
| Backend     | Laravel 13    |
| Admin Panel | Filament 5    |
| PHP Version | 8.3           |
| Frontend    | TailwindCSS 4 |
| Build Tool  | Vite          |
| Database    | MySQL         |
| Testing     | PHPUnit       |

## Dependencies

### Composer Packages

- `filament/filament` (~5.0) - Admin panel builder
- `laravel/framework` (^13.0) - Core framework
- `laravel/tinker` (^3.0) - REPL for Laravel

### Dev Dependencies

- `laravel/boost` (^2.2)
- `laravel/pint` (^1.27) - Code formatter
- `phpunit/phpunit` (^12.5.12)
- `fakerphp/faker` (^1.23)

### NPM Packages

- `tailwindcss` (^4.0.0)
- `@tailwindcss/vite` (^4.0.0)
- `laravel-vite-plugin` (^3.0.0)
- `vite` (^8.0.0)
- `concurrently` (^9.0.1)

## How Much Is Completed

### Date: 02/05/2026

**Description:**

- Media management system added:
    - MediaFile model created
    - MediaFiles Filament 5 resource with Create, Edit, List pages
    - Database migration for `media_files` table (title, file_path, file_type, file_size)
- Admin panel UI improvements:
    - Brand name set to "Sairox"
    - Collapsible navigation groups for Posts and Media
    - Custom CSS styling for sidebar navigation (tighter spacing, polished group labels, active item highlight)
- Database migration bug fix:
    - Fixed `$navigationGroup` type hint in MediaFileResource to match Filament 5 parent class (`UnitEnum` instead of `BackedEnum`)

---

### Date: 19/04/2026

**Description:**

The project is in its early stages with the following completed:

- Basic Laravel 13 application structure configured
- Post Model created with WordPress-like schema:
    - post_author, post_content, post_title, post_excerpt
    - post_status, comment_status, slug, post_type
    - comment_count, thumbnail
    - Soft deletes enabled
- Filament 5 admin panel setup for Posts management:
    - Create, Edit, List, View pages
    - Custom form schema and table configuration
- Database migrations:
    - Posts table with all fields
    - Thumbnail column added via migration
- Basic welcome page (blade template)
- MySQL database connection configured in .env
- Vite + TailwindCSS 4 frontend build pipeline
