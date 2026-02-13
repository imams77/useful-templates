# Vitest for TypeScript

Vitest is a fast, ESM-native test runner built for Vite projects with Jest-compatible API.

**Use Vitest when:**

- Using Vite as your build tool
- Need faster test execution
- Prefer native ESM support
- Working with modern frameworks (Vue 3, SvelteKit, Astro)

---

## Installation

```bash
npm install -D vitest
```

---

## Configuration

### `vite.config.ts` (or `vitest.config.ts`)

```typescript
import { defineConfig } from 'vitest/config';

export default defineConfig({
  test: {
    globals: true,
    environment: 'node',
    include: ['**/*.test.ts', '**/*.spec.ts'],
    exclude: ['node_modules', 'dist'],
    coverage: {
      provider: 'v8',
      reporter: ['text', 'json', 'html'],
      include: ['src/**/*.ts'],
      exclude: ['src/**/*.d.ts', 'src/**/*.test.ts', 'src/**/*.spec.ts'],
      thresholds: {
        branches: 75,
        functions: 90,
        lines: 80,
        statements: 80,
      },
    },
    clearMocks: true,
    restoreMocks: true,
  },
});
```

### `tsconfig.json` additions

```json
{
  "compilerOptions": {
    "types": ["vitest/globals", "node"]
  }
}
```

### Enable globals (optional)

If `globals: true` is set in config, you don't need imports:

```typescript
// No imports needed
describe('test', () => {
  it('works', () => {
    expect(true).toBe(true);
  });
});
```

Without globals:

```typescript
import { describe, it, expect } from 'vitest';
```

---

## Mocking

### Mock a Module

```typescript
import { vi } from 'vitest';

// Mock entire module
vi.mock('./externalService');

// Mock with implementation
vi.mock('./externalService', () => ({
  fetchData: vi.fn().mockResolvedValue({ data: 'mocked' }),
}));
```

### Mock with Type Safety

```typescript
import { vi, type MockedFunction } from 'vitest';
import { fetchData } from './externalService';

vi.mock('./externalService');

const mockFetchData = fetchData as MockedFunction<typeof fetchData>;

beforeEach(() => {
  mockFetchData.mockResolvedValue({ data: 'test' });
});
```

### Mock a Class

```typescript
import { vi, type MockedClass } from 'vitest';

vi.mock('./UserService');

import { UserService } from './UserService';

const MockedUserService = UserService as MockedClass<typeof UserService>;

beforeEach(() => {
  MockedUserService.mockClear();
  MockedUserService.prototype.getUser = vi.fn().mockResolvedValue({ id: 1 });
});
```

### Spy on Methods

```typescript
import { vi } from 'vitest';

const spy = vi.spyOn(object, 'method');
spy.mockReturnValue('mocked');

// Verify
expect(spy).toHaveBeenCalledWith('arg');

// Restore
spy.mockRestore();
```

### Mock Timers

```typescript
import { vi, beforeEach, afterEach, it, expect } from 'vitest';

beforeEach(() => {
  vi.useFakeTimers();
});

afterEach(() => {
  vi.useRealTimers();
});

it('should call callback after delay', () => {
  const callback = vi.fn();
  delayedFunction(callback, 1000);

  vi.advanceTimersByTime(1000);

  expect(callback).toHaveBeenCalled();
});
```

### Mock Date

```typescript
import { vi } from 'vitest';

beforeEach(() => {
  vi.setSystemTime(new Date('2024-01-01'));
});

afterEach(() => {
  vi.useRealTimers();
});
```

---

## Common Matchers

Vitest uses Jest-compatible matchers:

```typescript
// Equality
expect(value).toBe(exact);
expect(value).toEqual(deepEqual);
expect(value).toStrictEqual(strictDeepEqual);

// Truthiness
expect(value).toBeTruthy();
expect(value).toBeFalsy();
expect(value).toBeNull();
expect(value).toBeUndefined();
expect(value).toBeDefined();

// Numbers
expect(value).toBeGreaterThan(3);
expect(value).toBeLessThanOrEqual(5);
expect(value).toBeCloseTo(0.3, 5);

// Strings
expect(value).toMatch(/regex/);
expect(value).toContain('substring');

// Arrays
expect(array).toContain(item);
expect(array).toHaveLength(3);
expect(array).toContainEqual({ id: 1 });

// Objects
expect(object).toHaveProperty('key');
expect(object).toHaveProperty('nested.key', 'value');
expect(object).toMatchObject({ partial: 'match' });

// Exceptions
expect(() => fn()).toThrow();
expect(() => fn()).toThrow('specific message');
expect(() => fn()).toThrow(ErrorClass);

// Async
await expect(promise).resolves.toBe(value);
await expect(promise).rejects.toThrow();

// Mock calls
expect(mockFn).toHaveBeenCalled();
expect(mockFn).toHaveBeenCalledTimes(2);
expect(mockFn).toHaveBeenCalledWith('arg1', 'arg2');
expect(mockFn).toHaveBeenLastCalledWith('arg');
```

---

## Test Lifecycle

```typescript
import { beforeAll, afterAll, beforeEach, afterEach } from 'vitest';

beforeAll(() => {
  // Run once before all tests in this file
});

afterAll(() => {
  // Run once after all tests in this file
});

beforeEach(() => {
  // Run before each test
});

afterEach(() => {
  // Run after each test
});
```

---

## CLI Commands

```bash
# Run all tests
npx vitest run

# Watch mode (default)
npx vitest

# Run specific file
npx vitest helpers.test.ts

# Run tests matching pattern
npx vitest --testNamePattern="formatCurrency"

# Coverage report
npx vitest run --coverage

# UI mode (interactive)
npx vitest --ui

# Run only changed files
npx vitest --changed

# Verbose output
npx vitest --reporter=verbose
```

---

## package.json Scripts

```json
{
  "scripts": {
    "test": "vitest",
    "test:run": "vitest run",
    "test:coverage": "vitest run --coverage",
    "test:ui": "vitest --ui",
    "test:ci": "vitest run --coverage --reporter=junit"
  }
}
```

---

## Example: Testing helpers.ts with Vitest

```typescript
// helpers.ts
export function parseQueryString(query: string): Record<string, string> {
  if (!query) return {};
  return query
    .replace(/^\?/, '')
    .split('&')
    .reduce((acc, pair) => {
      const [key, value] = pair.split('=');
      if (key) acc[decodeURIComponent(key)] = decodeURIComponent(value || '');
      return acc;
    }, {} as Record<string, string>);
}

// helpers.test.ts
import { describe, it, expect } from 'vitest';
import { parseQueryString } from './helpers';

describe('parseQueryString', () => {
  it('should parse simple query string', () => {
    expect(parseQueryString('foo=bar&baz=qux')).toEqual({
      foo: 'bar',
      baz: 'qux',
    });
  });

  it('should handle leading question mark', () => {
    expect(parseQueryString('?foo=bar')).toEqual({ foo: 'bar' });
  });

  it('should handle empty value', () => {
    expect(parseQueryString('foo=')).toEqual({ foo: '' });
  });

  it('should handle encoded characters', () => {
    expect(parseQueryString('name=John%20Doe')).toEqual({ name: 'John Doe' });
  });

  it('should return empty object for empty string', () => {
    expect(parseQueryString('')).toEqual({});
  });

  it('should return empty object for null/undefined', () => {
    expect(parseQueryString(null as unknown as string)).toEqual({});
  });
});
```

---

## Example: Testing plugin.ts with Vitest

```typescript
// plugin.ts
export interface PluginContext {
  config: Record<string, unknown>;
  logger: { info: (msg: string) => void; error: (msg: string) => void };
}

export async function initializePlugin(ctx: PluginContext): Promise<boolean> {
  try {
    ctx.logger.info('Plugin initializing...');
    await new Promise((resolve) => setTimeout(resolve, 100));
    ctx.logger.info('Plugin initialized');
    return true;
  } catch (error) {
    ctx.logger.error('Plugin initialization failed');
    return false;
  }
}

// plugin.test.ts
import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest';
import { initializePlugin, PluginContext } from './plugin';

describe('initializePlugin', () => {
  let mockContext: PluginContext;

  beforeEach(() => {
    vi.useFakeTimers();
    mockContext = {
      config: {},
      logger: {
        info: vi.fn(),
        error: vi.fn(),
      },
    };
  });

  afterEach(() => {
    vi.useRealTimers();
  });

  it('should log initialization messages', async () => {
    const promise = initializePlugin(mockContext);
    vi.advanceTimersByTime(100);
    await promise;

    expect(mockContext.logger.info).toHaveBeenCalledWith('Plugin initializing...');
    expect(mockContext.logger.info).toHaveBeenCalledWith('Plugin initialized');
  });

  it('should return true on success', async () => {
    const promise = initializePlugin(mockContext);
    vi.advanceTimersByTime(100);
    const result = await promise;

    expect(result).toBe(true);
  });

  it('should not call error logger on success', async () => {
    const promise = initializePlugin(mockContext);
    vi.advanceTimersByTime(100);
    await promise;

    expect(mockContext.logger.error).not.toHaveBeenCalled();
  });
});
```

---

## Vitest vs Jest: Key Differences

| Feature | Jest | Vitest |
| ------- | ---- | ------ |
| Mock function | `jest.fn()` | `vi.fn()` |
| Mock module | `jest.mock()` | `vi.mock()` |
| Spy | `jest.spyOn()` | `vi.spyOn()` |
| Fake timers | `jest.useFakeTimers()` | `vi.useFakeTimers()` |
| Advance timers | `jest.advanceTimersByTime()` | `vi.advanceTimersByTime()` |
| Clear mocks | `jest.clearAllMocks()` | `vi.clearAllMocks()` |
| Reset mocks | `jest.resetAllMocks()` | `vi.resetAllMocks()` |
| Restore mocks | `jest.restoreAllMocks()` | `vi.restoreAllMocks()` |
| Mock type | `jest.MockedFunction` | `MockedFunction` from vitest |
| Set system time | N/A | `vi.setSystemTime()` |
