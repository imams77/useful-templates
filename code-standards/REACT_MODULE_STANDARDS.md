# Module Structure Guide

This guide explains the folder structure and conventions used in this module. Follow this pattern when creating or refactoring other modules.

## Folder Structure

```
module-name/
├── __fixtures__/          # Test data factories and pre-built collections
├── components/
│   └── pages/             # Page-level components
│   └── elements/          # custom components
├── constants/             # Static values and enums
├── domain/                # Pure business logic (framework-agnostic)
├── dto/                   # Data Transfer Objects (API payload types)
├── hooks/                 # React hooks (stateful logic)
├── locales/               # i18n translation files
├── routes/                # Route definitions
├── services/              # API calls and mutations
├── transform/             # Data transformation functions
├── types/                 # TypeScript type definitions
└── module-name.config.ts  # Module configuration
```

## Folder Purposes

### `__fixtures__/`
Contains factory functions and pre-built test data collections. Used across all test files to ensure consistent test data.

### `components/pages/`
Page-level React components that compose the UI. Keep components thin—delegate logic to hooks and domain functions.

### `components/elements/`
Reusable UI components specific to this module. Keep business logic minimal here.

### `constants/`
Static values, enums, and configuration that don't change at runtime.

### `domain/`
Pure business logic functions with **no React or framework dependencies**. These functions are easily testable and reusable. Contains the core algorithms and business rules of the module.

### `dto/`
TypeScript types that define the shape of API request/response payloads. Separates API contract from internal types.

### `hooks/`
React hooks that encapsulate stateful logic, side effects, and compose domain functions with React lifecycle. Keep hooks focused on a single responsibility.

### `locales/`
Translation files for internationalization.

### `routes/`
Route definitions and lazy-loaded component imports.

### `services/`
API call definitions using `react-query-kit` or similar. Contains only the API layer, no business logic.

### `transform/`
Functions that transform data between shapes (e.g., API response → UI model, form data → API payload). Keeps transformation logic isolated and testable.

### `types/`
Internal TypeScript types used within the module. Separate from DTOs which are API-specific.

## Test Structure

### Location
Tests live in `__tests__/` folders adjacent to the code they test.

### Fixtures
All test files import from `__fixtures__/index.ts` which provides:
1. **Factory functions** — Create single entities with sensible defaults and overrides
2. **Pre-built collections** — Common scenarios reused across tests
3. **Re-exported types and constants** — Convenience imports for test files

### What to Test

Focus on testing **pure logic and data transformations**:

- **Edge cases** — Null/undefined inputs, empty arrays, boundary conditions
- **Immutability** — Original data should not be mutated
- **Correctness** — Output matches expected shape and values

### Testable Layers

| Layer | Focus |
|-------|-------|
| `domain/` | Pure business logic, calculations, validations |
| `hooks/` | State changes, callback invocations, loading states |
| `transform/` | Data shape transformations, field mappings |

## Key Principles

1. **Domain logic is pure** — No React, no side effects, no API calls
2. **Hooks compose domain logic** — Hooks wire domain functions to React state/lifecycle
3. **Transforms are isolated** — Easy to test data transformations independently
4. **Fixtures are shared** — Single source of truth for test data
5. **Types are separated** — DTOs for API, types for internal use
