# SpecKit Frontend Constitution

> **Template for**: React, Svelte, Vue, or any frontend-focused project  
> **Use this when**: Building client-side applications, SPAs, or static sites

## Core Principles

### I. Security-First Architecture (NON-NEGOTIABLE)

Security is the foundational pillar of all development decisions. Every feature,
component, and integration MUST be designed with security considerations from
the outset.

**Rules**:
- All user inputs MUST be validated and sanitized before rendering
- XSS protections MUST be in place (use framework's built-in escaping)
- Authentication tokens MUST be stored securely (httpOnly cookies preferred, never localStorage for sensitive tokens)
- CSRF tokens MUST be implemented for state-changing operations
- Content Security Policy (CSP) headers MUST be configured
- Secrets and API keys MUST NEVER be exposed in client-side code
- Dependencies MUST be regularly audited for security vulnerabilities
- Third-party scripts MUST be loaded with integrity checks (SRI)

**Rationale**: Frontend applications are directly exposed to users and attackers.
Client-side security vulnerabilities can compromise user data and system integrity.

### II. Performance & User Experience

Applications MUST be optimized for fast load times and smooth interactions.
Performance directly impacts user satisfaction and conversion rates.

**Rules**:
- Initial bundle size MUST be kept under 200KB (gzipped)
- Time to Interactive (TTI) MUST be under 3 seconds on 3G networks
- Code splitting and lazy loading MUST be used for routes and heavy components
- Images MUST be optimized (WebP/AVIF with fallbacks, lazy loading, responsive sizes)
- Critical CSS MUST be inlined; non-critical CSS deferred
- JavaScript MUST be deferred or async-loaded when not critical
- Web Vitals (LCP, FID, CLS) MUST be monitored and meet "Good" thresholds
- Frontend caching strategies MUST be implemented (service workers, HTTP cache headers)

**Rationale**: Users abandon slow websites. Fast, responsive applications improve
engagement, SEO rankings, and business outcomes.

### III. Component Architecture & Maintainability

Code MUST be organized into reusable, testable components with clear responsibilities.
Components are the building blocks of maintainable frontend applications.

**Rules**:
- Components MUST follow Single Responsibility Principle (one component, one purpose)
- Business logic MUST be separated from presentation (hooks, stores, services)
- Shared components MUST be extracted to a component library or shared directory
- Component props/inputs MUST be strongly typed (TypeScript interfaces)
- Complex state logic MUST use appropriate state management (Context, Zustand, Redux, Svelte stores)
- Global state MUST be minimized; prefer component-level state when possible
- Component files MUST follow naming conventions: `ComponentName.tsx` or `ComponentName.svelte`
- Large components (>200 lines) MUST be refactored into smaller sub-components

**Rationale**: Well-structured components reduce bugs, improve reusability, and
make testing straightforward.

### IV. Type Safety with TypeScript

TypeScript MUST be used throughout the codebase to catch errors at compile time
and improve developer experience.

**Rules**:
- All component props MUST be typed with interfaces or types
- API response data MUST be typed (no implicit `any`)
- Event handlers MUST have properly typed parameters
- `any` type MUST be avoided; use `unknown` with type guards if needed
- Strict mode MUST be enabled in `tsconfig.json`
- Generic types SHOULD be used for reusable components and utilities
- TypeScript version MUST be kept up-to-date (5.x or later)

**Rationale**: Type safety prevents runtime errors, improves IDE support, and
serves as inline documentation for component APIs.

### V. Test Coverage (NON-NEGOTIABLE)

Testing is mandatory but follows a pragmatic approach: implementation first,
then comprehensive testing to ensure quality and prevent regression.

**Rules**:
- Unit tests MUST be written after implementation for components and utilities
- Component tests MUST verify rendering, user interactions, and edge cases
- Unit test coverage MUST exceed 80% for critical components and utilities
- E2E tests MUST cover critical user journeys (auth, checkout, core features)
- All interactive elements MUST have `data-testid` attributes for E2E testing
- Visual regression tests SHOULD be used for critical UI components
- Tests MUST run in CI/CD and block merges on failure

**Rationale**: Frontend bugs directly impact users. Tests ensure components work
as expected across browsers and prevent regressions during refactoring.

### VI. Accessibility (PREFERABLE)

Applications SHOULD be designed to be accessible to users with disabilities,
following WCAG guidelines where feasible. Accessibility is preferable but not
mandatory for all features.

**Preferred Practices**:
- Semantic HTML SHOULD be used (proper heading hierarchy, `<button>`, `<nav>`, etc.)
- Interactive elements SHOULD be keyboard navigable (tab order, Enter/Space support)
- Color contrast SHOULD meet WCAG AA standards (4.5:1 for normal text)
- ARIA labels SHOULD be provided for screen readers (`aria-label`, `aria-describedby`)
- Form inputs SHOULD have associated `<label>` elements
- Images SHOULD include descriptive `alt` text
- Focus indicators SHOULD be visible and clear
- Skip navigation links MAY be provided for complex layouts
- Error messages SHOULD be announced to screen readers (`role="alert"`)

**When to Prioritize**:
- Public-facing applications with diverse user bases
- E-commerce, government, or regulated industries
- Features where accessibility significantly improves UX for all users

**When Accessibility is Optional**:
- Internal tools with limited, trained user base
- Prototype or proof-of-concept applications
- Features under tight deadlines (can be retrofitted later)

**Rationale**: Accessible applications reach broader audiences and often provide
better UX for everyone (keyboard shortcuts, clear focus states, etc.).

### VII. Framework-Specific Standards

The project MUST follow best practices for the chosen frontend framework.

**React**:
- Functional components with hooks (no class components in new code)
- React 18+ concurrent features (Suspense, transitions) SHOULD be used
- Custom hooks MUST be extracted for reusable stateful logic
- `useCallback` and `useMemo` SHOULD be used judiciously (profile first)
- Context MUST NOT be overused (causes re-renders); prefer dedicated state management

**Svelte**:
- Svelte 4+ (or SvelteKit for full-stack) MUST be used
- Stores MUST be used for shared state (`writable`, `readable`, `derived`)
- Reactive statements (`$:`) SHOULD be used for derived values
- Component props MUST use `export let` with TypeScript types
- Actions and transitions SHOULD be used for DOM interactions and animations

**Vue**:
- Vue 3 Composition API MUST be used (not Options API in new code)
- Composables MUST be extracted for reusable logic
- `ref` and `reactive` MUST be used appropriately (prefer `ref` for primitives)
- Props MUST be typed with `defineProps<T>()` (TypeScript)
- `v-if` MUST be preferred over `v-show` unless toggling frequently

**Rationale**: Framework-specific patterns leverage each tool's strengths and
align with ecosystem conventions.

## Architecture Standards

### Project Structure

```
/
├── src/
│   ├── components/          # Reusable UI components
│   │   ├── common/          # Generic components (Button, Input, Card)
│   │   ├── features/        # Feature-specific components (UserProfile, ProductCard)
│   │   └── layout/          # Layout components (Header, Footer, Sidebar)
│   ├── pages/ or routes/    # Page/route components
│   ├── hooks/ or composables/ # Custom hooks (React) or composables (Vue)
│   ├── stores/              # State management (Zustand, Redux, Svelte stores)
│   ├── services/            # API clients, data fetching
│   ├── utils/               # Pure utility functions
│   ├── types/               # TypeScript type definitions
│   ├── styles/              # Global styles, CSS modules, theme
│   ├── assets/              # Images, fonts, static files
│   └── App.tsx              # Root component
├── public/                  # Static assets (served as-is)
├── tests/
│   ├── unit/                # Component and utility tests
│   ├── integration/         # Integration tests (multi-component)
│   └── e2e/                 # End-to-end tests (Playwright/Cypress)
├── .env.example             # Example environment variables
├── tsconfig.json            # TypeScript configuration
├── vite.config.ts           # Vite build configuration
└── package.json
```

### State Management

- **Local State**: Use component state for UI-only state (modals, toggles)
- **Shared State**: Use Context (React), stores (Svelte), or Pinia (Vue) for app-wide state
- **Server State**: Use React Query, SWR, or TanStack Query for API data caching
- **Form State**: Use React Hook Form, Formik (React), or native framework patterns

### Styling Approach

Choose ONE primary styling approach per project:

- **CSS Modules**: Scoped styles, good for component libraries
- **Tailwind CSS**: Utility-first, fast prototyping (configure purge for production)
- **Styled Components / Emotion**: CSS-in-JS, dynamic theming
- **Native Framework Styles**: Svelte `<style>` blocks (scoped), Vue SFCs

**Rules**:
- Global styles MUST be kept minimal (resets, typography, theme variables)
- Component styles MUST be scoped (CSS Modules, framework scoping, or CSS-in-JS)
- Unused CSS MUST be purged in production builds
- Theme variables (colors, spacing) MUST be defined in a central location

## Quality & Testing Standards

### Testing Strategy

1. **Component Tests** (Write After Implementation):
   - Test rendering with different props
   - Test user interactions (clicks, form submissions)
   - Test conditional rendering and edge cases
   - Use Testing Library (React/Svelte/Vue) for user-centric tests
   - Mock API calls and external dependencies

2. **E2E Tests** (Write for Critical User Journeys):
   - Test authentication flows (login, logout, registration)
   - Test core user workflows (create, read, update, delete)
   - Use Playwright or Cypress with TypeScript
   - Identify elements via `data-testid` attributes exclusively

3. **Visual Regression Tests** (Optional but Recommended):
   - Use Chromatic, Percy, or Playwright screenshots
   - Cover critical UI components and pages
   - Run on PR to catch unintended visual changes

### Component Test ID Convention

All interactive elements MUST include `data-testid` attributes:

```tsx
// ✅ Correct
<button data-testid="login-submit-btn">Login</button>
<input data-testid="email-input" type="email" />
<div data-testid="error-message" role="alert">{error}</div>

// ❌ Incorrect (no test ID)
<button>Login</button>
<input type="email" />
```

**Naming Convention**: `{element-purpose}-{element-type}` in kebab-case

### Code Quality Gates

All pull requests MUST pass the following checks before merging:

- [ ] ESLint passes with no errors or warnings
- [ ] Prettier formatting is applied
- [ ] TypeScript compilation succeeds with no errors
- [ ] Unit tests pass with minimum 80% coverage on new code
- [ ] E2E tests pass for affected user flows
- [ ] Bundle size analysis shows no significant increase (>10%)
- [ ] Lighthouse scores meet thresholds (Performance >90, Accessibility >90)
- [ ] No new security vulnerabilities (npm audit)

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
