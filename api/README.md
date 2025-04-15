# Star Wars API Backend

This is the backend component of the Star Wars Explorer application, built with Laravel and MySQL.

## Architecture Overview

The backend follows a RESTful API architecture using Laravel's robust routing and controller system. It serves data about Star Wars characters and movies, while tracking user interactions for analytics purposes.

## Key Features

- **RESTful API endpoints** - Clean, well-structured endpoints for accessing Star Wars data
- **Analytics tracking** - Automatic tracking of user searches and detail views
- **Middleware architecture** - Request handling through specialized middleware components
- **Database migrations** - Version-controlled database schema
- **Docker containerization** - Easy deployment and consistent environments

## Tech Stack

- **Laravel** - PHP framework for web applications
- **MySQL** - Relational database for data storage
- **Docker** - Containerization for development and deployment
- **PHP-FPM** - FastCGI process manager for PHP
- **Composer** - Dependency management
- **Artisan** - CLI for Laravel tasks
- **Eloquent ORM** - Object-relational mapping

## API Endpoints

### Search
- `GET /api/people?search={query}` - Search for characters
- `GET /api/movies?search={query}` - Search for movies

### Details
- `GET /api/people/{id}` - Get details for a specific character
- `GET /api/movies/{id}` - Get details for a specific movie

### Statistics
- `GET /api/statistics` - Get overall statistics
- `GET /api/statistics/searches` - Get search analytics
- `GET /api/statistics/detail-views` - Get detail view analytics
- `GET /api/statistics/performance` - Get API performance data
- `GET /api/statistics/traffic` - Get traffic analysis

## Database Structure

The database includes several key tables:

1. **people** - Star Wars characters data
2. **movies** - Star Wars films data
3. **api_activities** - Tracking user interactions with the API

### Tracking Data Model

The `ApiActivity` model captures:
- Event type (search, detail_view, etc.)
- Entity type (character, movie)
- Entity ID and name
- Search query (if applicable)
- Response time
- User info (if authenticated)
- Metadata (IP, user agent, etc.)

## Middleware

Custom middleware components include:

- **TrackApiActivity** - Records all API interactions for analytics

## Docker Setup

The application is containerized using Docker with:
- PHP 8.2 FPM container
- MySQL 8.0 database container
- Supervisor for background processes
- Cron for scheduled tasks

## Development

### Requirements
- Docker and Docker Compose
- PHP 8.2+ (for local development outside Docker)
- Composer

### Setup

```bash
# Start the Docker container
docker-compose up -d

# Install dependencies
composer install

# Run migrations
php artisan migrate

# Run the server
php artisan serve
```

### Key Files

- `app/Http/Controllers/` - API controllers
- `app/Models/` - Eloquent models
- `app/Http/Middleware/TrackApiActivity.php` - Analytics tracking
- `database/migrations/` - Database schema
- `routes/api.php` - API route definitions
- `docker/` - Docker configuration files

## Scheduled Tasks

The application uses Laravel's scheduler to:
- Generate daily analytics reports
- Clean up old tracking data
- Refresh cached data

## Performance Considerations

- Database indexes for frequently queried fields
- Response caching for common queries
- Query optimization for large datasets
- Pagination for large result sets

## Security

- CORS configuration for frontend access
- Rate limiting on API endpoints
- Input validation and sanitization
- Environment-based configuration

## Future Improvements

- Implement API versioning
- Add GraphQL support
- Enhance analytics with more detailed metrics
- Implement queue system for processing heavy tasks
- Add OpenAPI/Swagger documentation
