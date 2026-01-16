# Implementation Plan: [FEATURE]

**Branch**: `[###-feature-name]` | **Date**: [DATE] | **Spec**: [link]
**Input**: Feature specification from `/specs/[###-feature-name]/spec.md`

**Note**: This template is filled in by the `/speckit.plan` command. See `.specify/templates/commands/plan.md` for the execution workflow.

## Summary

[Extract from feature spec: primary requirement + technical approach from research]

## Technical Context

<!--
  ACTION REQUIRED: Replace the content in this section with the technical details
  for the project. Per Constitution, certain standards are mandated.
  
  MANDATED BY CONSTITUTION:
  - Language: TypeScript 5.x (ES2022)
  - Monorepo: Nx with pnpm
  - Backend: Node.js LTS + Express.js + TypeORM/Prisma
  - Frontend: React, Svelte, or Vue (choose one per app)
  - Testing: Jest (unit), Playwright/Cypress (E2E), Supertest (API integration)
  - Code Quality: ESLint + Prettier
-->

**Language/Version**: TypeScript 5.x targeting ES2022  
**Monorepo Tool**: Nx with pnpm package manager  
**Backend Framework**: Node.js (LTS) + Express.js  
**ORM/Database**: [TypeORM or Prisma] with [PostgreSQL/MySQL/etc. or NEEDS CLARIFICATION]  
**Frontend Framework**: [React/Svelte/Vue or NEEDS CLARIFICATION]  
**Testing Stack**:  
  - Unit: Jest with ts-jest  
  - Integration: Supertest for APIs  
  - E2E: [Playwright or Cypress or NEEDS CLARIFICATION]  
**Code Quality**: ESLint + Prettier (enforced via Git hooks + CI)  
**Project Type**: [Nx monorepo with apps/backend + apps/frontend + packages]  
**Performance Goals**: [domain-specific, e.g., API p95 <500ms, bundle <200KB or NEEDS CLARIFICATION]  
**Constraints**: [domain-specific, e.g., <200ms p95, offline support, SSR requirements or NEEDS CLARIFICATION]  
**Scale/Scope**: [domain-specific, e.g., 10k concurrent users, 50 API endpoints or NEEDS CLARIFICATION]  
**Rendering Strategy**: [CSR/SSG preferred; SSR if justified by SEO/performance needs]

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

[Gates determined based on constitution file]

## Project Structure

### Documentation (this feature)

```text
specs/[###-feature]/
├── plan.md              # This file (/speckit.plan command output)
├── research.md          # Phase 0 output (/speckit.plan command)
├── data-model.md        # Phase 1 output (/speckit.plan command)
├── quickstart.md        # Phase 1 output (/speckit.plan command)
├── contracts/           # Phase 1 output (/speckit.plan command)
└── tasks.md             # Phase 2 output (/speckit.tasks command - NOT created by /speckit.plan)
```

### Source Code (repository root)
<!--
  ACTION REQUIRED: Replace the placeholder tree below with the concrete layout
  for this feature. Delete unused options and expand the chosen structure with
  real paths (e.g., apps/api, packages/ui-components). The delivered plan must
  not include Option labels.
  
  Per Constitution: This is an Nx monorepo with apps/ and packages/libs/ structure.
-->

```text
# [REMOVE IF UNUSED] Option 1: Nx Monorepo - Full Stack Web App (STANDARD)
apps/
├── backend/
│   └── api/                    # Express.js backend
│       ├── src/
│       │   ├── models/         # TypeORM/Prisma entities
│       │   ├── services/       # Business logic
│       │   ├── controllers/    # API route handlers
│       │   └── middleware/     # Express middleware
│       └── tests/
│           ├── unit/           # Unit tests (.spec.ts)
│           ├── integration/    # Integration tests (.spec.ts)
│           └── e2e/            # E2E API tests (.e2e.ts)
└── frontend/
    └── web/                    # React/Svelte/Vue app
        ├── src/
        │   ├── components/     # UI components (with data-testid)
        │   ├── pages/          # Page components
        │   ├── services/       # API clients
        │   └── lib/            # Utilities
        └── tests/
            ├── unit/           # Component unit tests
            └── e2e/            # Playwright/Cypress E2E tests

packages/ or libs/
├── ui-components/              # Shared React/Svelte/Vue components
│   ├── src/
│   └── tests/
├── core/                       # Shared business logic
│   ├── src/
│   └── tests/
├── types/                      # Shared TypeScript types
│   └── src/
└── utils/                      # Shared utilities
    ├── src/
    └── tests/

# [REMOVE IF UNUSED] Option 2: Nx Monorepo - Multiple Apps
apps/
├── backend/
│   ├── api/                    # Main API
│   └── admin-api/              # Admin-specific API (if needed)
└── frontend/
    ├── web/                    # Main web app
    └── admin/                  # Admin dashboard (if needed)

packages/ or libs/
└── [same as Option 1]

# [REMOVE IF UNUSED] Option 3: Nx Monorepo - Single Library Package
packages/ or libs/
└── [package-name]/
    ├── src/
    │   ├── lib/                # Library code
    │   └── index.ts            # Public API exports
    └── tests/
        └── unit/               # Unit tests
```

**Structure Decision**: [Document the selected structure and reference the real
directories captured above. Per Constitution, this MUST be an Nx monorepo with
TypeScript, and follow the apps/packages organizational model.]

## Complexity Tracking

> **Fill ONLY if Constitution Check has violations that must be justified**

| Violation | Why Needed | Simpler Alternative Rejected Because |
|-----------|------------|-------------------------------------|
| [e.g., 4th project] | [current need] | [why 3 projects insufficient] |
| [e.g., Repository pattern] | [specific problem] | [why direct DB access insufficient] |
