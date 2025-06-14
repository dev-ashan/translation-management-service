# Translation Management Service

A Laravel-based translation management system with RESTful API for managing translations, locales, and tags.

## Quick Start

```bash
# Clone and install
git clone [repository-url]
cd translation-management-service
composer install
cp .env.example .env
php artisan key:generate

# Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=translation_management
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Setup database
php artisan migrate --seed
```

## Key Features

- Multi-language support with locale management
- Translation key-value management
- Tag-based organization
- RESTful API with OpenAPI documentation
- JWT Authentication
- Caching system
- Soft deletes

## API Documentation

Access at: `http://localhost:8000/api/documentation`

## Available Commands

```bash
# Generate test data
php artisan generate:test-data

# Clear translation cache
php artisan translations:clear-cache
```

## Testing

```bash
# All tests
php artisan test

# Specific suites
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
php artisan test --testsuite=Performance
```

## Database Migrations

Key migrations:
- `2024_03_13_000001_create_locales_table.php`
- `2024_03_13_000002_create_tags_table.php`
- `2024_03_13_000003_create_translations_table.php`
- `2024_06_13_000004_add_deleted_at_to_tags_table.php`
- `2024_06_13_000005_add_deleted_at_to_translations_table.php`

## Project Structure

```
app/
├── Http/
│   ├── Controllers/Api/  # API Controllers
│   ├── Requests/         # Form Requests
│   └── Resources/        # API Resources
├── Models/               # Eloquent Models
├── Repositories/         # Data Access Layer
├── Services/            # Business Logic
└── Traits/              # Reusable Traits
```

## Security & Performance

- JWT Authentication
- Input validation
- Rate limiting
- Translation caching
- Efficient queries
- Pagination
- Soft deletes

## Requirements

- PHP >= 8.1
- MySQL >= 8.0
- Composer
- Node.js & NPM

## License

MIT License
