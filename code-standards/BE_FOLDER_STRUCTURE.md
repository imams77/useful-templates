# Backend Folder Structure

A clean architecture blueprint for backend applications. Language and framework agnostic.

---

## Overview

```
project-root/
├── constants/        # Shared constants and enumerations
├── database/         # Database connections, migrations, seeders
├── docs/             # API documentation
├── helpers/          # Cross-cutting concerns (middleware, response builders)
├── models/           # Domain entities / database models
├── modules/          # Feature modules (core of the application)
├── router/           # Route registration and dependency wiring
├── utils/            # General-purpose utilities
├── worker/           # Background job processing
└── entrypoint        # Application entry point
```

---

## Directory Purposes

| Directory     | What goes here                                                                 |
| ------------- | ------------------------------------------------------------------------------ |
| `constants/`  | Error messages, status enums, config keys — organized per module               |
| `database/`   | DB/cache connection setup, migration files, seed data                          |
| `docs/`       | Generated API specs (OpenAPI/Swagger). Do not edit manually                    |
| `helpers/`    | Middleware (auth, permissions, pagination), request/response utilities, external service wrappers |
| `models/`     | Shared domain entities that map to database tables                             |
| `modules/`    | Self-contained feature modules — each with its own layers                      |
| `router/`     | Composes all modules: wires repos → usecases → handlers, registers routes      |
| `utils/`      | Config loader, logger, validators, type helpers, file utilities                |
| `worker/`     | Job dispatcher, worker pool, job definitions                                   |

---

## Module Architecture

Each module follows clean architecture with dependencies flowing **inward**: delivery → usecase → repository.

```
modules/{module_name}/
│
├── repository          # Interface defining data access contract
├── usecase             # Interface defining business logic contract
│
├── delivery/
│   └── http/           # HTTP handlers, route registration, request/response handling
│
├── dto/                # Data Transfer Objects (request, response, filters, mappers)
│
├── repository/         # Data access implementation
│   └── searches/       # Search column definitions (optional)
│
├── usecase/            # Business logic implementation
│
├── tasks/              # Background task handlers (optional)
│
└── tests/              # Module-specific tests (optional)
```

### Layer Responsibilities

**Interfaces** (module root) — Contracts for repository and usecase. Live at the root to prevent circular dependencies. Any module-specific abstractions (e.g., token verification) also go here.

**delivery/** — Receives HTTP requests, validates input, calls the usecase, returns formatted responses. Applies middleware (auth, permissions, pagination) per route.

**dto/** — Shapes data between layers. Naming convention:

| Type     | Convention               | Purpose                              |
| -------- | ------------------------ | ------------------------------------ |
| Request  | `ReqCreate{Entity}`      | Create payload with validation rules |
| Request  | `ReqUpdate{Entity}`      | Update payload (nullable fields for partial updates) |
| Request  | `Req{Entity}IndexFilter` | Query filters (search, sort, pagination) |
| Response | `Resp{Entity}`           | Single entity response shape         |
| Response | `Resp{Entity}Index`      | List response shape (may include joined fields) |

Includes mapper functions to convert models → response DTOs.

**repository/** — Database operations. Split helpers for clarity:

| File              | Purpose                                      |
| ----------------- | -------------------------------------------- |
| Main repository   | CRUD, queries, data retrieval                |
| Filter helpers    | Build query conditions from filter DTOs      |
| Sort helpers      | Map API sort fields to database columns      |
| Error helpers     | Translate DB errors to domain errors         |
| Searches          | Define searchable columns and thresholds     |

**usecase/** — Business rules, multi-repo coordination, file uploads, input validation beyond simple schema checks. Receives repositories via constructor injection.

**tasks/** — Async job handlers for the module (e.g., email delivery). Self-contained with own dependency setup.

**tests/** — Unit and integration tests with shared fixtures and mocks.

---

## Module Examples

### Standard Module

```
modules/announcement/
├── repository
├── usecase
├── delivery/
│   └── http/
│       └── announcement_handler
├── dto/
│   └── announcement_dto
├── repository/
│   ├── announcement_repository
│   ├── filter_helpers
│   ├── sort_helpers
│   └── searches/
│       └── announcement_search
└── usecase/
    └── announcement_usecase
```

### Extended Module (with tasks and tests)

```
modules/auth/
├── repository
├── usecase
├── token                              # Module-specific abstraction
├── delivery/
│   └── http/
│       ├── auth_handler
│       └── reset_password_handler
├── dto/
│   └── auth_dto
├── repository/
│   ├── auth_repository
│   ├── cache_repository
│   └── reset_password_repository
├── usecase/
│   ├── login
│   ├── logout
│   ├── profile
│   ├── refresh_token
│   └── reset_password
├── tasks/
│   └── password_reset_email_handler
└── tests/
    ├── login_test
    ├── logout_test
    ├── profile_test
    └── reset_password_test
```

---

## Adding a New Module

1. Create the module directory with `delivery/http/`, `dto/`, `repository/`, `usecase/`
2. Define repository and usecase interfaces at the module root
3. Create DTOs — request schemas, response schemas, mappers
4. Implement the repository with filter/sort/search helpers as needed
5. Implement the usecase with business logic
6. Create handlers that wire middleware and delegate to the usecase
7. Add constants in `constants/`
8. Add the model in `models/` if a new table is needed
9. Wire repo → usecase → handler in `router/`
10. Create a database migration if needed

---

## Conventions

| Principle                          | Description                                                                                  |
| ---------------------------------- | -------------------------------------------------------------------------------------------- |
| **Interface-driven design**        | Interfaces at module root, implementations in subdirectories. Enforces dependency inversion.  |
| **Constructor injection**          | Dependencies passed through constructors. No global state.                                   |
| **One module per feature**         | Each feature gets its own module with the full layer stack.                                   |
| **DTOs at API boundaries**         | Never expose raw models. Map through DTOs to decouple internals from the public contract.    |
| **Nullable fields for updates**    | Update DTOs use nullable/optional fields to distinguish "not sent" from "set to zero/empty". |
| **Constants over magic strings**   | All error messages, statuses, and keys live in `constants/`.                                 |
| **Middleware per route**           | Auth and permission checks are applied per route/group, not globally.                        |
| **Query logic in helpers**         | Filters, sorts, and searches live in dedicated files, not inlined in repository methods.     |
| **Self-contained async tasks**     | Background tasks initialize their own dependencies and can run independently.                |