# SpecKit SSR Constitution

> **Template for**: Next.js, Nuxt, SvelteKit, Remix, or any SSR/hybrid rendering framework  
> **Use this when**: Building server-side rendered applications with SEO requirements or hybrid rendering needs

## Core Principles

### I. Security-First Architecture (NON-NEGOTIABLE)

Security is the foundational pillar of all development decisions. SSR applications
have both client and server attack surfaces that MUST be protected.

**Rules**:
- All user inputs MUST be validated on BOTH client and server sides
- XSS protections MUST be in place (framework's built-in escaping, CSP headers)
- Authentication tokens MUST be stored securely (httpOnly cookies, never localStorage)
- Server-side secrets MUST NEVER be exposed to client (use server-only modules)
- Environment variables MUST be prefixed appropriately (NEXT_PUBLIC_, NUXT_PUBLIC_, etc.)
- API routes MUST implement authentication, authorization, and rate limiting
- CSRF tokens MUST be implemented for state-changing operations
- SQL injection and NoSQL injection MUST be prevented (use parameterized queries, ORM)
- Content Security Policy (CSP) MUST be configured restrictively
- Security headers MUST be set (X-Frame-Options, X-Content-Type-Options, etc.)
- Dependencies MUST be regularly audited for security vulnerabilities
- Server-side rendering MUST sanitize user-generated content before rendering

**Rationale**: SSR applications run code on both server and client, expanding the
attack surface. Secrets accessible on the server must be protected from client exposure.

### II. Performance & Web Vitals

SSR applications MUST leverage server rendering for optimal performance and SEO.
Core Web Vitals directly impact search rankings and user experience.

**Rules**:
- Largest Contentful Paint (LCP) MUST be under 2.5 seconds
- First Input Delay (FID) MUST be under 100ms
- Cumulative Layout Shift (CLS) MUST be under 0.1
- Time to First Byte (TTFB) MUST be under 600ms (server response time)
- Images MUST be optimized using framework's image optimization (next/image, nuxt/image)
- Images MUST use modern formats (WebP/AVIF) with fallbacks
- Critical CSS MUST be inlined automatically by the framework
- JavaScript MUST be code-split and lazy-loaded for routes and components
- Static generation (SSG) MUST be preferred over SSR when content is not dynamic
- Incremental Static Regeneration (ISR) SHOULD be used for semi-static content (Next.js)
- Server components MUST be used to reduce client bundle size (Next.js App Router, React Server Components)
- Streaming SSR SHOULD be used to improve perceived performance (Suspense)
- Caching strategies MUST be implemented (CDN, server cache, stale-while-revalidate)

**Rationale**: SSR provides better initial load performance and SEO, but requires
careful optimization to avoid slow server responses and hydration overhead.

### III. Rendering Strategy & Data Fetching

The appropriate rendering strategy MUST be chosen for each page/route based on
content dynamism, SEO needs, and performance requirements.

**Rules**:
- **Static Generation (SSG)** MUST be used for content that rarely changes (marketing pages, blogs, docs)
- **Server-Side Rendering (SSR)** MUST be used for personalized or frequently changing content
- **Incremental Static Regeneration (ISR)** SHOULD be used for semi-dynamic content (product pages, news)
- **Client-Side Rendering (CSR)** MAY be used for authenticated dashboards or highly interactive UIs
- Data fetching MUST use framework-specific patterns (getStaticProps, getServerSideProps, load functions, loaders)
- API calls from server components MUST NOT use absolute URLs (direct function calls preferred)
- Parallel data fetching MUST be used when possible (Promise.all, parallel loaders)
- Data MUST be fetched at the appropriate level (page-level for critical, component-level for non-critical)
- Waterfall requests MUST be avoided (fetch in parallel, not sequentially)
- Database queries from server components MUST be optimized (use ORM, indexing)

**Rationale**: Choosing the right rendering strategy balances performance, SEO,
and development complexity. Inappropriate choices lead to slow pages or stale content.

### IV. Component Architecture & Code Separation

Code MUST be clearly separated between server and client. Components MUST follow
framework-specific patterns for server/client boundaries.

**Rules**:
- Server-only code MUST be marked explicitly ('use server', server/ directory, .server.ts suffix)
- Client-only code MUST be marked explicitly ('use client', client/ directory, .client.ts suffix)
- Server components MUST NOT use client-side APIs (useState, useEffect, browser APIs)
- Client components MUST NOT directly access server-side resources (databases, file system)
- Shared utilities MUST be framework-agnostic (no server or client dependencies)
- Environment variables MUST be prefixed correctly to avoid server secret exposure
- API routes MUST be in designated directories (app/api/, pages/api/, routes/)
- Components MUST follow Single Responsibility Principle
- Large components (>200 lines) MUST be refactored into smaller sub-components
- Server and client components MUST have clear boundaries (minimize client components)

**Framework-Specific Patterns**:

**Next.js (App Router)**:
- Server components are default; use 'use client' directive sparingly
- Server actions MUST use 'use server' directive
- Server-only code MUST use server-only package

**Nuxt**:
- Server-only code MUST be in server/ directory
- API routes MUST be in server/api/
- Server-only composables MUST have .server.ts suffix

**SvelteKit**:
- Server-only code MUST be in +page.server.ts or +layout.server.ts
- API routes MUST be in +server.ts files
- Form actions MUST be defined in load functions

**Rationale**: Clear separation prevents accidental exposure of server secrets and
ensures optimal bundle sizes (server code not shipped to client).

### V. Type Safety with TypeScript

TypeScript MUST be used throughout the codebase (client, server, and API routes)
to catch errors at compile time.

**Rules**:
- All component props MUST be typed with interfaces or types
- API request/response payloads MUST be typed (no implicit `any`)
- Server function parameters and return values MUST be typed
- Environment variables MUST be typed and validated (Zod, t3-env)
- `any` type MUST be avoided; use `unknown` with type guards if needed
- Strict mode MUST be enabled in `tsconfig.json`
- Generic types SHOULD be used for reusable components and utilities
- TypeScript version MUST be kept up-to-date (5.x or later)
- Type imports MUST use `import type` for type-only imports

**Rationale**: Type safety prevents runtime errors, improves IDE support, and
serves as inline documentation for component and API contracts.

### VI. Test Coverage (NON-NEGOTIABLE)

Testing is mandatory but follows a pragmatic approach: implementation first,
then comprehensive testing to ensure quality and prevent regression.

**Rules**:
- Unit tests MUST be written after implementation for components and utilities
- Unit test coverage MUST exceed 80% for critical components and utilities
- Server-side logic (API routes, server actions) MUST have integration tests
- E2E tests MUST cover critical user journeys (auth, checkout, core features)
- E2E tests MUST test both server-rendered and client-rendered states
- All interactive elements MUST have `data-testid` attributes for E2E testing
- Hydration errors MUST be caught in tests (server HTML mismatch)
- API routes MUST be tested with different auth states and edge cases
- Visual regression tests SHOULD be used for critical UI components
- Tests MUST run in CI/CD and block merges on failure

**Rationale**: SSR applications have complex rendering lifecycles (server → hydration → client).
Tests must verify behavior across all states.

### VII. SEO & Metadata Management

SSR applications MUST implement proper SEO practices to maximize discoverability
and search engine rankings.

**Rules**:
- Dynamic metadata MUST be set per-page (title, description, OG tags)
- Metadata MUST use framework-specific patterns (Metadata API, useHead, MetaTags component)
- Structured data (JSON-LD) SHOULD be added for rich snippets (products, articles, events)
- Sitemap MUST be generated and kept up-to-date (dynamic if content changes)
- Robots.txt MUST be configured appropriately
- Canonical URLs MUST be set to avoid duplicate content penalties
- Open Graph and Twitter Card tags MUST be included for social sharing
- Alt text MUST be provided for all images
- Semantic HTML MUST be used (proper heading hierarchy, nav, main, footer)
- Page speed MUST meet Core Web Vitals thresholds (LCP, FID, CLS)

**Rationale**: SSR's primary advantage is SEO. Proper metadata and performance
optimization maximize search visibility and click-through rates.

### VIII. Accessibility (PREFERABLE)

Applications SHOULD be designed to be accessible to users with disabilities,
following WCAG guidelines where feasible. Accessibility is preferable but not
mandatory for all features.

**Preferred Practices**:
- Semantic HTML SHOULD be used (proper heading hierarchy, meaningful elements)
- Interactive elements SHOULD be keyboard navigable (tab order, focus states)
- Color contrast SHOULD meet WCAG AA standards (4.5:1 for normal text)
- ARIA labels SHOULD be provided for screen readers where appropriate
- Form inputs SHOULD have associated `<label>` elements
- Images SHOULD include descriptive `alt` text
- Focus indicators SHOULD be visible and clear
- Skip navigation links MAY be provided for complex layouts
- Error messages SHOULD be announced to screen readers (`role="alert"`)
- Server-rendered content SHOULD be accessible before JavaScript loads

**When to Prioritize**:
- Public-facing applications with diverse user bases
- E-commerce, government, or regulated industries
- Content-heavy sites (blogs, news, documentation)

**When Accessibility is Optional**:
- Internal tools with limited, trained user base
- Prototype or proof-of-concept applications
- Features under tight deadlines (can be retrofitted later)

**Rationale**: SSR applications are often public-facing and benefit from broad
accessibility. Screen readers work better with server-rendered semantic HTML.

## Architecture Standards

### Project Structure

**Next.js (App Router)**:
```
/
├── app/
│   ├── (auth)/              # Route groups for layouts
│   │   ├── login/
│   │   └── register/
│   ├── (dashboard)/
│   │   ├── profile/
│   │   └── settings/
│   ├── api/                 # API routes
│   │   ├── auth/
│   │   └── users/
│   ├── layout.tsx           # Root layout
│   ├── page.tsx             # Home page
│   └── globals.css
├── components/
│   ├── server/              # Server components
│   ├── client/              # Client components ('use client')
│   └── shared/              # Shared utilities
├── lib/
│   ├── db/                  # Database client and queries
│   ├── auth/                # Authentication logic
│   ├── utils/               # Utility functions
│   └── types/               # TypeScript types
├── public/                  # Static assets
├── tests/
│   ├── unit/
│   ├── integration/
│   └── e2e/
├── .env.local               # Local environment variables
├── next.config.js
├── tsconfig.json
└── package.json
```

**Nuxt**:
```
/
├── app/
│   ├── components/          # Auto-imported components
│   ├── composables/         # Auto-imported composables
│   ├── layouts/             # Layouts (default.vue, etc.)
│   ├── middleware/          # Route middleware
│   ├── pages/               # File-based routing
│   │   ├── index.vue
│   │   ├── about.vue
│   │   └── users/
│   │       └── [id].vue
│   ├── plugins/             # Vue plugins
│   └── app.vue              # Root component
├── server/
│   ├── api/                 # API routes
│   │   ├── auth/
│   │   └── users/
│   ├── middleware/          # Server middleware
│   └── utils/               # Server utilities
├── public/                  # Static assets
├── tests/
├── .env
├── nuxt.config.ts
├── tsconfig.json
└── package.json
```

**SvelteKit**:
```
/
├── src/
│   ├── lib/
│   │   ├── components/      # Reusable components
│   │   ├── server/          # Server-only utilities
│   │   └── utils/           # Shared utilities
│   ├── routes/              # File-based routing
│   │   ├── +page.svelte
│   │   ├── +page.server.ts
│   │   ├── api/
│   │   │   └── users/
│   │   │       └── +server.ts
│   │   └── [slug]/
│   │       ├── +page.svelte
│   │       └── +page.server.ts
│   ├── app.html             # HTML template
│   └── app.css
├── static/                  # Static assets
├── tests/
├── .env
├── svelte.config.js
├── tsconfig.json
└── package.json
```

### Data Fetching Patterns

**Next.js (App Router)**:
```typescript
// Server Component (default)
async function ProductPage({ params }: { params: { id: string } }) {
  const product = await db.product.findUnique({ where: { id: params.id } });
  return <ProductDetails product={product} />;
}

// Server Action
'use server'
async function createProduct(formData: FormData) {
  const data = Object.fromEntries(formData);
  await db.product.create({ data });
  revalidatePath('/products');
}

// API Route
export async function GET(request: Request) {
  const products = await db.product.findMany();
  return Response.json(products);
}
```

**Nuxt**:
```typescript
// pages/products/[id].vue
<script setup lang="ts">
const route = useRoute()
const { data: product } = await useFetch(`/api/products/${route.params.id}`)
</script>

// server/api/products/[id].get.ts
export default defineEventHandler(async (event) => {
  const id = getRouterParam(event, 'id')
  return await db.product.findUnique({ where: { id } })
})
```

**SvelteKit**:
```typescript
// routes/products/[id]/+page.server.ts
export const load = async ({ params }) => {
  const product = await db.product.findUnique({ where: { id: params.id } });
  return { product };
};

// routes/api/products/+server.ts
export const GET = async () => {
  const products = await db.product.findMany();
  return json(products);
};
```

### State Management

- **Server State**: Fetch directly in server components/load functions
- **Client State**: Use framework-specific patterns (useState, ref, stores)
- **Global State**: Use Context (React), Pinia (Nuxt), or Svelte stores
- **Form State**: Use React Hook Form, Nuxt form helpers, or SvelteKit form actions
- **Cache Management**: Use framework's built-in revalidation (revalidatePath, nuxtApp.hooks, invalidate)

### Environment Variables & Secrets

**Rules**:
- Server-only secrets MUST NOT be prefixed with public indicator
- Client-accessible variables MUST be explicitly prefixed (NEXT_PUBLIC_, NUXT_PUBLIC_, PUBLIC_)
- Environment variables MUST be validated at startup (Zod schema)
- Different .env files MUST exist for dev, staging, production

**Example**:
```bash
# ✅ Server-only (safe)
DATABASE_URL=postgresql://...
API_SECRET=abc123...

# ✅ Client-accessible (explicit)
NEXT_PUBLIC_API_URL=https://api.example.com
NUXT_PUBLIC_SITE_URL=https://example.com

# ❌ Wrong (will be exposed to client in some frameworks)
PUBLIC_DATABASE_URL=postgresql://...  # NEVER DO THIS
```

## Quality & Testing Standards

### Testing Strategy

1. **Component Tests** (Write After Implementation):
   - Test server components with server-side rendering
   - Test client components with user interactions
   - Test hydration behavior (server HTML matches client)
   - Use Testing Library with framework-specific renderers
   - Mock API calls and server functions

2. **API/Server Function Tests** (Write After Implementation):
   - Test API routes with different HTTP methods
   - Test authentication and authorization
   - Test error handling and validation
   - Use Supertest or framework testing utilities

3. **E2E Tests** (Write for Critical User Journeys):
   - Test full user workflows with real server rendering
   - Test JavaScript-disabled scenarios (progressive enhancement)
   - Use Playwright or Cypress with TypeScript
   - Identify elements via `data-testid` attributes exclusively

4. **Performance Tests** (Recommended):
   - Use Lighthouse CI to enforce Web Vitals thresholds
   - Test bundle sizes and prevent regressions
   - Test Time to First Byte (TTFB) for SSR pages

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
- [ ] API/server function tests pass
- [ ] E2E tests pass for affected user flows
- [ ] Lighthouse scores meet thresholds (Performance >90, Accessibility >90, SEO >95)
- [ ] Bundle size analysis shows no significant increase (>10%)
- [ ] No hydration errors in development
- [ ] No server secrets exposed to client bundle
- [ ] No new security vulnerabilities (npm audit)

## Performance Optimization Checklist

- [ ] Images optimized with framework's image component
- [ ] Font optimization enabled (next/font, Nuxt font module)
- [ ] Appropriate rendering strategy chosen per route
- [ ] Parallel data fetching used where possible
- [ ] Suspense boundaries added for async components
- [ ] Server components used to reduce client bundle
- [ ] Static generation used for non-dynamic pages
- [ ] CDN caching configured for static assets
- [ ] Database queries optimized (indexing, N+1 prevention)
- [ ] Core Web Vitals monitored and meet thresholds

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

**Version**: 1.0.0 | **Ratified**: 2025-11-10 | **Last Amended**: 2025-11-10
