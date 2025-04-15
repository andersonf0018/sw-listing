# Star Wars Frontend

This is the frontend component of the Star Wars Explorer application, built with Next.js 15 and React 19.

## Architecture Overview

The application follows a component-based architecture with state management handled through Zustand stores. It uses React Query for data fetching and caching, providing a smooth user experience with optimistic updates and error handling.

## Key Features

- **Real-time search** - Search for Star Wars characters and movies with instant feedback
- **Responsive design** - Mobile-first approach with adaptive layouts
- **Accessible components** - ARIA attributes and keyboard navigation support
- **Type-safe codebase** - Full TypeScript implementation
- **Unit tests** - Component testing with Jest and React Testing Library

## Tech Stack

- **Next.js 15** - React framework with app router
- **React 19** - UI library with hooks
- **TypeScript** - Type safety
- **TailwindCSS** - Utility-first CSS framework
- **Zustand** - Lightweight state management
- **React Query** - Data fetching, caching, and synchronization
- **Jest & React Testing Library** - Testing framework

## State Management

### Stores

The application uses Zustand for state management with the following stores:

1. **SearchBoxStore** (`stores/search-box.store.ts`)
   - Manages search input and filter selection
   - Persists search state between navigation

2. **ResultsStore** (`stores/results.store.ts`)
   - Handles search results and pagination
   - Maintains loading states

### Why use Zustand?

Zustand was chosen as the state management solution primarily to solve common React state challenges:

1. **Eliminates prop drilling** - No need to pass state and callback props through multiple component layers, making the component hierarchy cleaner and more maintainable.

2. **Component decoupling** - Components can directly access the store without being tightly coupled to their parents, enabling better separation of concerns.

3. **Predictable state updates** - All state changes happen through defined actions, making the application behavior more predictable and easier to debug.

4. **Lightweight solution** - Unlike Redux, Zustand has minimal boilerplate and a tiny footprint (less than 1KB), providing just what's needed without unnecessary complexity.

5. **Simplified testing** - Components that use Zustand can be easily tested by mocking the store, making unit tests more straightforward to write.

### API Integration

The `api.ts` file provides a centralized client for making API requests to the backend. It:
- Sets up base URL and headers
- Handles common error patterns
- Provides type-safe interfaces for API responses

## Component Structure

```
components/
├── details-box/      # Character/movie details display
├── error-box/        # Error handling and display
├── loading-box/      # Loading indicators
├── results/          # Search results display
│   ├── results-box.tsx       # Results container
│   └── results-item.tsx      # Individual result item
├── search-box/       # Search input and filters
├── topbar/           # Application header
└── ui/               # Reusable UI components
    ├── button.tsx
    ├── input.tsx
    └── radio-group.tsx
```

### Component Design Decisions

1. **Separation of Concerns**
   - Each component focuses on a single responsibility
   - UI components are separate from business logic

2. **Composition Over Inheritance**
   - Components are composed together rather than extended
   - Small, focused components are combined to build complex UIs

3. **Progressive Enhancement**
   - Core functionality works without JavaScript
   - Enhanced experience with JavaScript enabled

## Testing Strategy

- **Unit Tests** - Testing individual components in isolation
- **Component Tests** - Testing component interactions
- **Integration Tests** - Testing data flow between components

### IMPORTANT:

In this project, I've implemented unit tests for only three components (DetailsBox, ErrorBox, and LoadingBox) as a demonstration of my testing strategy and approach. In a production environment, I would ensure comprehensive test coverage for all components.

The limited test coverage is solely due to time constraints for this project. The implemented tests showcase:

1. Component rendering tests
2. Proper DOM structure verification
3. User interaction testing
4. Accessibility validation
5. Mock implementation for external dependencies

These patterns can be extended to all components following the same testing principles. The current test implementation serves as a representative sample of how I approach testing in React applications.

## Running the Project

```bash
# Install dependencies
npm install

# Run development server
npm run dev

# Build for production
npm run build

# Run tests
npm run test
```

## Environment Variables

- `NEXT_PUBLIC_API_URL` - URL of the backend API

## Future Improvements

- Implement server-side rendering for search results
- Add end-to-end tests with Cypress
- Implement dark mode support
- Add internationalization (i18n) support
