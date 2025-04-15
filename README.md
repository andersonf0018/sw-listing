# Star Wars Explorer

A full-stack application for exploring the Star Wars universe, built with Next.js, React, and Laravel.

## Features

- Search for Star Wars characters and movies
- View detailed information about characters and films
- Responsive design for mobile and desktop
- Backend API with analytics tracking

## Tech Stack

### Frontend

- **Next.js 15** - React framework
- **React 19** - UI library
- **TailwindCSS** - Styling
- **Zustand** - State management
- **React Query** - Data fetching and caching
- **Jest & React Testing Library** - Testing

### Backend

- **Laravel** - PHP framework
- **MySQL** - Database
- **Docker** - Containerization

### IMPORTANT

You can see a more detailed README about each project inside the `app` and `api` folder.

## Getting Started

### Prerequisites

- Docker and Docker Compose
- Node.js (v18+)
- npm or yarn

### Installation

1. Start the Docker containers:

```bash
docker-compose up -d
```

2. Set the database credentials on `api/.env`. You can see the default database credentials on `docker-compose.yml`

3. The application will be available at:
   - Frontend: http://localhost:3000
   - Backend API: http://localhost:9000
   - PHPMyAdmin: http://localhost:8080

## Development

### Frontend

To run the frontend in development mode:

```bash
cd app
npm install
npm run dev
```

### Backend

The backend API is automatically set up through Docker. If you need to manually run commands:

```bash
docker-compose exec backend php artisan <command>
```

### Running Tests

Frontend tests:

```bash
cd app
npm run test
```

## Project Structure

```
lawnstarter/
├── app/                  # Frontend Next.js application
│   ├── src/
│   │   ├── app/          # Next.js app directory
│   │   ├── components/   # UI components
│   │   ├── hooks/        # Custom React hooks
│   │   ├── lib/          # Utility functions
│   │   ├── stores/       # Zustand state stores
│   │   └── types/        # TypeScript type definitions
├── api/                  # Backend Laravel application
│   ├── app/              # Laravel application code
│   │   ├── Http/         # Controllers, middleware
│   │   ├── Models/       # Database models
│   │   └── ...
│   ├── database/         # Database migrations and seeds
│   └── ...
└── docker-compose.yml    # Docker configuration
```

## Analytics

The application tracks user interactions, including:

- Search queries
- Detail page views
- API response times

This data is accessible through the statistics endpoints.

## License

[MIT License](LICENSE)
