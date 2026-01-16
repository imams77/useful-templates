# SpecKit Backend Constitution

> **Template for**: Node.js API servers, microservices, or backend-focused projects  
> **Use this when**: Building REST APIs, GraphQL servers, or backend services

## Core Principles

### I. Security-First Architecture (NON-NEGOTIABLE)

Security is the foundational pillar of all development decisions. Every feature,
component, and integration MUST be designed with security considerations from
the outset.

**Rules**:
- All user inputs MUST be validated and sanitized at entry points (use validation libraries)
- Authentication and authorization MUST be implemented for all protected endpoints
- Secrets and sensitive data MUST NEVER be committed to version control (use environment variables)
- JWT tokens MUST use secure signing algorithms (RS256, not HS256 with weak secrets)
- Passwords MUST be hashed using bcrypt or Argon2 (never plain text or MD5)
- SQL injection MUST be prevented (use parameterized queries, ORM)
- Rate limiting MUST be implemented to prevent abuse (e.g., express-rate-limit)
- CORS MUST be configured restrictively (whitelist origins, not `*`)
- Security headers MUST be set (Helmet.js for Express)
- Dependencies MUST be regularly audited for vulnerabilities (npm audit, Snyk)
- API keys MUST be validated and rotated regularly
- Sensitive data MUST be encrypted at rest and in transit (HTTPS, TLS)

**Rationale**: Backend services are the gateway to business logic and data.
Security vulnerabilities can lead to data breaches, financial loss, and legal liability.

### II. Performance & Scalability

Applications MUST be designed to handle load efficiently and scale horizontally
as traffic grows. Performance directly impacts user experience and operational costs.

**Rules**:
- API endpoints MUST respond within acceptable latency thresholds (p95 < 500ms)
- Database queries MUST be optimized with proper indexing and query planning
- N+1 query problems MUST be avoided (use eager loading, batch queries)
- Connection pooling MUST be configured for databases and external services
- Caching MUST be implemented at appropriate layers (Redis, in-memory, CDN)
- Applications MUST be stateless to enable horizontal scaling (no in-process session storage)
- Long-running tasks MUST be offloaded to background workers (Bull, BullMQ, etc.)
- Circuit breakers MUST be implemented for external service calls
- Pagination MUST be used for large data sets (limit/offset or cursor-based)
- Streaming MUST be used for large file uploads/downloads

**Rationale**: Slow backends create poor user experiences. Scalable architecture
ensures the system can grow without costly rewrites.

### III. Maintainability & Clean Code

Code MUST be written for humans first, machines second. The codebase is a
long-term asset that will be read and modified far more than it is written.

**Rules**:
- DRY (Don't Repeat Yourself) principle MUST be applied - extract shared logic to utilities
- Functions and services MUST have single, clear responsibilities (Single Responsibility Principle)
- Business logic MUST be separated from HTTP layer (use service/controller pattern)
- Code MUST be self-documenting with clear naming; comments explain "why" not "what"
- Magic numbers and strings MUST be replaced with named constants or config
- Complex logic MUST be broken into smaller, testable units
- TypeScript MUST be used for type safety
- API documentation MUST be generated and kept up-to-date (Swagger/OpenAPI)
- Error handling MUST be centralized (global error handler middleware)

**Rationale**: Maintainable code reduces technical debt, onboarding time, and
bug introduction rates. Clean code is easier to test, debug, and evolve.

### IV. API Design & Consistency

APIs MUST follow RESTful principles (or GraphQL best practices) and provide
consistent, predictable interfaces for consumers.

**Rules**:
- RESTful APIs MUST use standard HTTP methods (GET, POST, PUT, PATCH, DELETE)
- HTTP status codes MUST be used correctly (200, 201, 400, 401, 403, 404, 500, etc.)
- Resource naming MUST be plural nouns (`/users`, `/products`, not `/getUser`)
- API versioning MUST be implemented via URL path (`/api/v1/`) or headers
- Request/response payloads MUST be validated (Zod, class-validator, Joi)
- Error responses MUST follow consistent structure (error code, message, details)
- API endpoints MUST be idempotent where appropriate (PUT, DELETE)
- Pagination, filtering, and sorting MUST be supported for list endpoints
- HATEOAS MAY be used for discoverability (optional)
- GraphQL schemas MUST be versioned and documented if using GraphQL

**Rationale**: Consistent APIs reduce integration errors, improve developer
experience, and make maintenance easier.

### V. Database & Data Integrity

Data persistence MUST be reliable, performant, and maintain integrity under
all conditions.

**Rules**:
- ORMs (TypeORM, Prisma, Drizzle, Sequelize) MUST be used for type safety and migrations
- Database migrations MUST be versioned, tested, and applied via CI/CD
- Transactions MUST be used for multi-step operations to ensure atomicity
- Foreign key constraints MUST be used to maintain referential integrity
- Indexes MUST be created for frequently queried columns (analyze query patterns)
- Soft deletes SHOULD be used instead of hard deletes for audit trails
- Database backups MUST be automated and tested regularly
- Connection pooling MUST be configured appropriately (max connections, timeouts)
- Raw SQL MAY be used for complex queries but MUST use parameterized queries

**Rationale**: Data is the most valuable asset. Poor data integrity leads to
inconsistencies, bugs, and loss of trust.

### VI. Test Coverage (NON-NEGOTIABLE)

Testing is mandatory but follows a pragmatic approach: implementation first,
then comprehensive testing to ensure quality and prevent regression.

**Rules**:
- Unit tests MUST be written after implementation for all business logic
- Unit test coverage MUST exceed 80% for critical paths (services, utilities)
- Integration tests MUST verify API endpoints with real database (test instance)
- Integration tests MUST use test databases (not production or shared dev databases)
- Test fixtures and seed data MUST be maintained for consistent test state
- Tests MUST be isolated (no shared state between tests)
- Tests MUST run in CI/CD and block merges on failure
- Mock external services (third-party APIs) in unit tests
- E2E tests MAY be used to verify critical workflows (optional for backend-only)

**Rationale**: Tests catch regressions, document behavior, and enable confident
refactoring. Writing tests after implementation allows for rapid prototyping.

### VII. Logging, Monitoring & Observability

Applications MUST provide visibility into their runtime behavior for debugging,
monitoring, and auditing.

**Rules**:
- Structured logging MUST be used (JSON format, not plain text)
- Log levels MUST be used appropriately (DEBUG, INFO, WARN, ERROR)
- Sensitive data MUST NOT be logged (passwords, tokens, PII)
- Request IDs MUST be generated and included in logs for tracing
- Error logs MUST include stack traces and context
- Application metrics MUST be exposed (response times, error rates, throughput)
- Health check endpoints MUST be implemented (`/health`, `/ready`)
- Distributed tracing SHOULD be implemented for microservices (OpenTelemetry)
- Log aggregation MUST be set up (ELK, Datadog, CloudWatch, etc.)

**Rationale**: Observability enables quick debugging, proactive monitoring, and
incident response. Logs and metrics are essential for production systems.

### VIII. Environment & Configuration Management

Configuration MUST be externalized and environment-specific. Applications MUST
work across dev, staging, and production without code changes.

**Rules**:
- Environment variables MUST be used for all configuration (database URLs, API keys, etc.)
- `.env.example` file MUST be provided with all required variables documented
- Secrets MUST NEVER be committed to version control (use `.gitignore` for `.env`)
- Configuration validation MUST happen at startup (fail fast on missing vars)
- Separate configs MUST exist for dev, staging, production environments
- Feature flags SHOULD be used for gradual rollouts (optional)
- Default values MAY be provided for non-sensitive config (ports, log levels)

**Rationale**: Externalized configuration enables deployment flexibility,
security, and environment-specific behavior.

## Architecture Standards

### Project Structure

```
/
├── src/
│   ├── controllers/         # HTTP request handlers (Express routes)
│   ├── services/            # Business logic layer
│   ├── repositories/        # Data access layer (database queries)
│   ├── models/              # Database models (ORM entities)
│   ├── middlewares/         # Express middlewares (auth, validation, error handling)
│   ├── utils/               # Pure utility functions
│   ├── types/               # TypeScript type definitions
│   ├── config/              # Configuration loading and validation
│   ├── validators/          # Request validation schemas (Zod, Joi, etc.)
│   └── app.ts               # Express app initialization
├── tests/
│   ├── unit/                # Unit tests (services, utils)
│   ├── integration/         # Integration tests (API endpoints)
│   └── fixtures/            # Test data and mocks
├── prisma/ (or migrations/) # Database schema and migrations
├── .env.example             # Example environment variables
├── tsconfig.json            # TypeScript configuration
├── package.json
└── README.md
```

### Layered Architecture

The application MUST follow a layered architecture pattern:

1. **Controller Layer** (HTTP):
   - Handle HTTP requests/responses
   - Validate request payloads
   - Call service layer
   - Return formatted responses

2. **Service Layer** (Business Logic):
   - Implement business rules and logic
   - Coordinate between repositories
   - Handle transactions
   - No HTTP concerns

3. **Repository Layer** (Data Access):
   - Interact with database via ORM
   - Execute queries and return data
   - No business logic

**Benefits**: Separation of concerns, testability, and maintainability.

### Dependency Injection (Optional but Recommended)

Use dependency injection for services and repositories:

```typescript
// ✅ Good (testable, flexible)
class UserService {
  constructor(private userRepo: UserRepository) {}
  async getUser(id: string) { return this.userRepo.findById(id); }
}

// ❌ Bad (hard to test, tight coupling)
class UserService {
  async getUser(id: string) {
    const userRepo = new UserRepository();
    return userRepo.findById(id);
  }
}
```

## Quality & Testing Standards

### Testing Strategy

1. **Unit Tests** (Write After Implementation):
   - Test services, utilities, and business logic
   - Mock dependencies (repositories, external APIs)
   - Use Jest with ts-jest for TypeScript support
   - Aim for >80% coverage on critical code paths

2. **Integration Tests** (Write After Implementation):
   - Test API endpoints end-to-end with real database (test instance)
   - Use Supertest for HTTP request testing
   - Seed database with fixtures before tests
   - Clean up database after tests

3. **Load Tests** (Optional but Recommended for Production):
   - Use k6, Artillery, or JMeter
   - Test under expected and peak loads
   - Identify bottlenecks and performance issues

### Code Quality Gates

All pull requests MUST pass the following checks before merging:

- [ ] All linting rules pass (ESLint + project-specific rules)
- [ ] Code formatting is consistent (Prettier)
- [ ] TypeScript compilation succeeds with no errors
- [ ] Unit tests pass with minimum 80% coverage on new code
- [ ] Integration tests pass
- [ ] API documentation is updated (Swagger/OpenAPI)
- [ ] No new security vulnerabilities introduced (npm audit)
- [ ] Build succeeds

### Error Handling Pattern

All errors MUST be handled consistently:

```typescript
// Custom error classes
class AppError extends Error {
  constructor(
    public statusCode: number,
    public message: string,
    public code?: string
  ) {
    super(message);
  }
}

// Global error handler middleware
app.use((err: Error, req: Request, res: Response, next: NextFunction) => {
  if (err instanceof AppError) {
    return res.status(err.statusCode).json({
      error: { code: err.code, message: err.message }
    });
  }
  // Log unexpected errors
  logger.error('Unexpected error', { error: err, requestId: req.id });
  return res.status(500).json({
    error: { code: 'INTERNAL_ERROR', message: 'An unexpected error occurred' }
  });
});
```

## Governance

### Constitution Authority

This constitution supersedes all other development practices, guidelines, and
preferences. When conflicts arise between this document and other sources, this
constitution takes precedence.

### Amendment Process

1. Proposed amendments MUST be documented with rationale and impact analysis
2. Amendments require approval from project maintainers
3. Major amendments (principle additions/removals) MUST include migration plan
4. Amendment history MUST be tracked via version increments:
   - **MAJOR**: Breaking changes, principle removals, or fundamental philosophy shifts
   - **MINOR**: New principles added, expanded guidance, new standards
   - **PATCH**: Clarifications, typo fixes, non-semantic refinements

### Development Guidance

For runtime development workflow and command-specific guidance, refer to:
- `.specify/templates/plan-template.md` for implementation planning
- `.specify/templates/spec-template.md` for feature specification
- `.specify/templates/tasks-template.md` for task breakdown

**Version**: 1.0.0 | **Ratified**: 2025-11-09 | **Last Amended**: 2025-11-09
