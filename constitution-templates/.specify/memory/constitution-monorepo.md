# SpecKit Monorepo Constitution

> **Template for**: Nx, Turborepo, or other monorepo projects with multiple apps and packages  
> **Use this when**: Building a workspace with shared libraries, multiple frontends, and/or backends

## Core Principles

### I. Security-First Architecture (NON-NEGOTIABLE)

Security is the foundational pillar of all development decisions. Every feature,
component, and integration MUST be designed with security considerations from
the outset.

**Rules**:
- All user inputs MUST be validated and sanitized at entry points
- Authentication and authorization MUST be implemented for all protected resources
- Secrets and sensitive data MUST NEVER be committed to version control
- Dependencies MUST be regularly audited and updated for security vulnerabilities
- API endpoints MUST implement rate limiting and input validation
- Security headers MUST be configured for all web applications
- SQL injection, XSS, and CSRF protections MUST be in place
- Shared security packages (auth, validation) MUST be used across all apps

**Rationale**: Security breaches compromise user trust, data integrity, and
system availability. Retrofitting security is exponentially more costly than
building it in from the start.

### II. Performance & Scalability

Applications MUST be designed to perform efficiently and scale horizontally as
demand grows. Performance is a feature, not an afterthought.

**Rules**:
- Database queries MUST be optimized with proper indexing and query planning
- API endpoints MUST respond within acceptable latency thresholds (p95 < 500ms)
- Frontend bundle sizes MUST be monitored and kept minimal through code splitting and lazy loading
- Static rendering and client-side rendering MUST be preferred when appropriate to reduce server load
- Caching strategies MUST be implemented at appropriate layers (CDN, API, database)
- Applications MUST be stateless to enable horizontal scaling
- Performance budgets MUST be established and monitored in CI/CD
- Build caching MUST be leveraged (Nx computation cache, Turborepo cache)

**Rationale**: Poor performance drives users away. Scalability ensures the
system grows with business needs without architectural rewrites.

### III. Maintainability & Code Reusability

Code MUST be written for humans first, machines second. Shared code MUST be
extracted to packages to prevent duplication and ensure consistency.

**Rules**:
- DRY (Don't Repeat Yourself) principle MUST be applied - shared logic belongs in reusable packages
- Functions and components MUST have single, clear responsibilities (Single Responsibility Principle)
- Code MUST be self-documenting with clear naming; comments explain "why" not "what"
- Magic numbers and strings MUST be replaced with named constants (preferably in shared packages)
- Complex logic MUST be broken into smaller, testable units
- TypeScript MUST be used for type safety across the monorepo
- Linting and formatting rules MUST be enforced via CI/CD
- Circular dependencies MUST be avoided and monitored

**Rationale**: Maintainable code reduces technical debt, onboarding time, and
bug introduction rates. Shared packages ensure consistency and reduce duplication.

### IV. Monorepo Organization Best Practices

The project MUST follow monorepo best practices (Nx, Turborepo, or similar) to
ensure consistent tooling, efficient dependency management, and code reusability.

**Rules**:
- Backend applications MUST reside in `apps/backend/*` or `apps/api/*`
- Frontend applications MUST reside in `apps/frontend/*` or `apps/web/*`
- Reusable code MUST be extracted to `packages/*` or `libs/*` with clear boundaries
- Shared UI components MUST be in a dedicated package (e.g., `packages/ui`)
- Shared business logic MUST be in dedicated packages (e.g., `packages/core`, `packages/utils`)
- Shared types MUST be in a dedicated package (e.g., `packages/types`)
- Dependency graph MUST be kept acyclic (no circular dependencies)
- Each package MUST have a clear public API and proper exports (`package.json` exports field)
- Internal packages MUST NOT be published to npm (use `"private": true`)
- Apps MUST NOT depend on other apps (only on shared packages)

**Rationale**: Proper monorepo structure prevents code duplication, enables
atomic changes across apps, and improves build caching and CI performance.

### V. Test Coverage (NON-NEGOTIABLE)

Testing is mandatory but follows a pragmatic approach: implementation first,
then comprehensive testing to ensure quality and prevent regression.

**Rules**:
- Unit tests MUST be written after implementation for all business logic
- Unit test coverage MUST exceed 80% for critical paths
- Integration tests MUST verify interactions between layers (API ↔ database, service ↔ service)
- E2E tests MUST cover critical user journeys end-to-end
- All interactive elements (buttons, forms, divs requiring interaction) MUST have proper test IDs (data-testid) for E2E testing
- Test fixtures and mocks MUST be organized in dedicated directories (or shared test-utils package)
- Tests MUST run in CI/CD and block merges on failure
- Affected tests MUST be run automatically based on dependency graph

**Rationale**: Tests catch regressions, document behavior, and enable confident
refactoring. Writing tests after implementation allows for rapid prototyping
while maintaining quality.

### VI. E2E & Integration Test Preparation

Components and APIs MUST be designed with testability in mind. Infrastructure
for E2E and integration testing MUST be in place from project inception.

**Rules**:
- All UI components MUST include `data-testid` attributes for reliable selection in E2E tests
- API contracts MUST be documented and versioned for contract testing
- Test environments MUST mirror production architecture
- Database seeding scripts MUST be maintained for consistent test data
- Integration tests MUST use dedicated test databases, not production or shared dev databases
- E2E tests MUST be parallelizable and independent (no shared state)
- Visual regression testing SHOULD be considered for critical UI components
- Shared test utilities MUST be in a dedicated package (e.g., `packages/test-utils`)

**Rationale**: Preparation enables fast, reliable test execution. Test IDs
decouple tests from implementation details, reducing maintenance burden.

### VII. Technology Stack Consistency

The monorepo MUST maintain a consistent, well-supported technology stack to
reduce complexity and leverage team expertise.

**Rules**:
- **Backend**: Node.js (LTS version) with Express.js, NestJS, or Fastify
- **Database**: PostgreSQL or MySQL with TypeORM, Prisma, or Drizzle ORM
- **Frontend**: React, Svelte, or Vue (can vary per-app, but document in README)
- **Language**: TypeScript 5.x targeting ES2022 for all code (frontend and backend)
- **Build Tool**: Nx or Turborepo with appropriate plugins
- **Testing**: Jest for unit tests, Playwright or Cypress for E2E tests, Supertest for API integration tests
- **Code Quality**: ESLint + Prettier enforced via Git hooks and CI
- **Package Manager**: pnpm (fast, disk-efficient) or npm workspaces
- **Version Control**: Git with conventional commits for automatic changelog generation

**Rationale**: Standardization reduces cognitive load, simplifies tooling, and
enables knowledge transfer across teams.

### VIII. Accessibility (PREFERABLE)

Applications SHOULD be designed to be accessible to users with disabilities,
following WCAG guidelines where feasible. Accessibility is preferable but not
mandatory for all features.

**Preferred Practices**:
- Semantic HTML SHOULD be used (proper heading hierarchy, meaningful elements)
- Interactive elements SHOULD be keyboard navigable (tab order, focus states)
- Color contrast SHOULD meet WCAG AA standards for text readability
- ARIA labels SHOULD be provided for screen reader users where appropriate
- Form inputs SHOULD have associated labels
- Images SHOULD include alt text describing content
- Focus indicators SHOULD be visible for keyboard navigation
- Skip navigation links MAY be provided for complex layouts
- Shared accessibility utilities SHOULD be in a dedicated package (e.g., `packages/a11y`)

**When to Prioritize**:
- Public-facing applications with diverse user bases
- Government or regulated industries with accessibility requirements
- Applications targeting broad demographics

**When Accessibility is Optional**:
- Internal tools with limited, trained user base
- Prototype or proof-of-concept applications
- Features under tight deadlines (can be retrofitted later)

**Rationale**: Accessible applications reach broader audiences and provide
better user experiences for everyone.

## Architecture Standards

### Monorepo Structure

```
/
├── apps/
│   ├── backend/              # Node.js backend apps
│   │   ├── api/              # Main API server
│   │   └── admin-api/        # Admin API (if needed)
│   └── frontend/             # Frontend applications
│       ├── web/              # Main web app (React/Svelte/Vue)
│       ├── admin/            # Admin dashboard
│       └── mobile/           # Mobile app (React Native, optional)
├── packages/ (or libs/)
│   ├── ui/                   # Shared UI component library
│   ├── core/                 # Shared business logic
│   ├── utils/                # Shared utilities
│   ├── types/                # Shared TypeScript types
│   ├── config/               # Shared configuration (ESLint, TS, etc.)
│   ├── auth/                 # Shared authentication logic
│   ├── db/                   # Database schemas, migrations, ORM config
│   └── test-utils/           # Shared test helpers
├── tools/
│   └── scripts/              # Build, deployment, and automation scripts
├── .specify/                 # SpecKit templates and memory
├── nx.json / turbo.json      # Monorepo tool configuration
├── package.json              # Root package.json with workspaces
├── tsconfig.base.json        # Shared TypeScript config
└── README.md                 # Project overview and setup instructions
```

### Package Boundaries & Dependencies

**Allowed Dependencies**:
- Apps → Packages (✅ apps can depend on packages)
- Packages → Packages (✅ packages can depend on other packages)

**Prohibited Dependencies**:
- Apps → Apps (❌ apps must NOT depend on other apps)
- Packages → Apps (❌ packages must NOT depend on apps)
- Circular dependencies (❌ A → B → A is forbidden)

**Enforcement**:
- Use Nx boundary rules (`nx.json` tags) or Turborepo workspace dependencies
- Run dependency graph checks in CI (`nx graph` or similar)

### API Design

- RESTful APIs MUST follow standard HTTP methods and status codes
- GraphQL MAY be used if justified by requirements (complex queries, reduced over-fetching)
- API versioning MUST be implemented via URL path (`/api/v1/`) or headers
- Request/response schemas MUST be validated (Zod, class-validator, or similar)
- Error responses MUST follow consistent structure (defined in shared package)
- API types MUST be shared between frontend and backend (use shared `packages/types`)

### Database & ORM

- ORMs (TypeORM, Prisma, Drizzle) MUST be used for type safety and migration management
- Database schema MUST be in a shared package (e.g., `packages/db`)
- Database migrations MUST be versioned and applied via CI/CD
- Raw SQL MAY be used for complex queries with proper parameterization
- Database connection pooling MUST be configured appropriately
- Seed scripts MUST be maintained for local development and testing

## Quality & Testing Standards

### Testing Strategy

1. **Unit Tests** (Write After Implementation):
   - Test pure functions, business logic, utilities
   - Mock external dependencies (database, APIs, filesystem)
   - Use Jest with ts-jest for TypeScript support
   - Aim for >80% coverage on critical code paths
   - Shared test utilities MUST be in `packages/test-utils`

2. **Integration Tests** (Write After Implementation):
   - Test API endpoints end-to-end with real database (test instance)
   - Verify service layer interactions with repositories
   - Use Supertest for HTTP request testing
   - Seed database with fixtures for consistent test state

3. **E2E Tests** (Write for Critical User Journeys):
   - Test full user workflows in browser environment
   - Use Playwright or Cypress with TypeScript
   - Run against deployed staging environment or local dev stack
   - Identify elements via `data-testid` attributes exclusively

### Code Quality Gates

All pull requests MUST pass the following checks before merging:

- [ ] All linting rules pass (ESLint + project-specific rules)
- [ ] Code formatting is consistent (Prettier)
- [ ] TypeScript compilation succeeds with no errors (across all packages and apps)
- [ ] Unit tests pass with minimum 80% coverage on new code
- [ ] Integration tests pass (if applicable)
- [ ] E2E tests pass for affected user flows
- [ ] Dependency graph remains acyclic (no circular dependencies)
- [ ] No new security vulnerabilities introduced (npm audit, Snyk, or similar)
- [ ] Build succeeds for affected projects (use Nx/Turborepo affected commands)

### Component Test ID Convention

All interactive elements MUST include `data-testid` attributes:

```tsx
// ✅ Correct
<button data-testid="submit-form-btn">Submit</button>
<input data-testid="email-input" type="email" />
<div data-testid="error-message" role="alert">{error}</div>

// ❌ Incorrect (no test ID)
<button>Submit</button>
<input type="email" />
```

**Naming Convention**: `{element-purpose}-{element-type}` in kebab-case

## Development Workflow

### Adding a New Package

1. Create package directory in `packages/` or `libs/`
2. Initialize with `package.json`, `tsconfig.json`, and `README.md`
3. Define clear public API in main entry point (e.g., `src/index.ts`)
4. Update root `tsconfig.base.json` paths if needed
5. Add appropriate tags for Nx boundary enforcement
6. Document package purpose and usage in README

### Adding a New App

1. Create app directory in `apps/backend/` or `apps/frontend/`
2. Initialize with appropriate framework (Express, React, Svelte, etc.)
3. Configure build and serve targets in `project.json` (Nx) or `turbo.json`
4. Add dependencies on shared packages
5. Document app purpose, setup, and deployment in README

### Versioning & Releases

- Use semantic versioning (semver) for all packages and apps
- Use conventional commits for automatic changelog generation
- Use tools like `changesets` or `lerna` for coordinated package releases
- Tag releases in Git with version numbers

## Governance

### Constitution Authority

This constitution supersedes all other development practices, guidelines, and
preferences. When conflicts arise between this document and other sources, this
constitution takes precedence.

### Amendment Process

1. Proposed amendments MUST be documented with rationale and impact analysis
2. Amendments require approval from project maintainers
3. Major amendments (principle additions/removals) MUST include migration plan for existing code
4. Amendment history MUST be tracked via version increments:
   - **MAJOR**: Breaking changes, principle removals, or fundamental philosophy shifts
   - **MINOR**: New principles added, expanded guidance, new standards
   - **PATCH**: Clarifications, typo fixes, non-semantic refinements

### Compliance & Review

- All code reviews MUST verify compliance with these principles
- Violations MUST be documented and justified (technical debt log)
- Quarterly reviews MUST assess constitution adherence and propose amendments as needed
- New team members MUST be onboarded with constitution review

### Development Guidance

For runtime development workflow and command-specific guidance, refer to:
- `.specify/templates/plan-template.md` for implementation planning
- `.specify/templates/spec-template.md` for feature specification
- `.specify/templates/tasks-template.md` for task breakdown

**Version**: 1.0.0 | **Ratified**: 2025-11-09 | **Last Amended**: 2025-11-09
