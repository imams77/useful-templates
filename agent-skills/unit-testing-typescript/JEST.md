# Jest for TypeScript

Jest is a mature testing framework with extensive mocking capabilities and a large ecosystem.

**Use Jest when:**

- Working on established projects already using Jest
- Need extensive mocking capabilities
- Using Create React App, Next.js, or similar
- Require snapshot testing

---

## Installation

```bash
npm install -D jest ts-jest @types/jest
```

---

## Configuration

### `jest.config.ts`

```typescript
import type { Config } from 'jest';

const config: Config = {
  preset: 'ts-jest',
  testEnvironment: 'node',
  roots: ['<rootDir>/src'],
  testMatch: ['**/*.test.ts', '**/*.spec.ts'],
  moduleFileExtensions: ['ts', 'js', 'json'],
  collectCoverageFrom: [
    'src/**/*.ts',
    '!src/**/*.d.ts',
    '!src/**/*.test.ts',
    '!src/**/*.spec.ts',
  ],
  coverageThreshold: {
    global: {
      branches: 75,
      functions: 90,
      lines: 80,
      statements: 80,
    },
  },
  clearMocks: true,
  resetMocks: true,
};

export default config;
```

### `tsconfig.json` additions

```json
{
  "compilerOptions": {
    "types": ["jest", "node"],
    "esModuleInterop": true
  }
}
```

---

## Mocking

### Mock a Module

```typescript
// Mock entire module
jest.mock('./externalService');

// Mock with implementation
jest.mock('./externalService', () => ({
  fetchData: jest.fn().mockResolvedValue({ data: 'mocked' }),
}));
```

### Mock with Type Safety

```typescript
import { fetchData } from './externalService';

jest.mock('./externalService');

const mockFetchData = fetchData as jest.MockedFunction<typeof fetchData>;

beforeEach(() => {
  mockFetchData.mockResolvedValue({ data: 'test' });
});
```

### Mock a Class

```typescript
jest.mock('./UserService');

import { UserService } from './UserService';

const MockedUserService = UserService as jest.MockedClass<typeof UserService>;

beforeEach(() => {
  MockedUserService.mockClear();
  MockedUserService.prototype.getUser = jest.fn().mockResolvedValue({ id: 1 });
});
```

### Spy on Methods

```typescript
const spy = jest.spyOn(object, 'method');
spy.mockReturnValue('mocked');

// Verify
expect(spy).toHaveBeenCalledWith('arg');

// Restore
spy.mockRestore();
```

### Mock Timers

```typescript
beforeEach(() => {
  jest.useFakeTimers();
});

afterEach(() => {
  jest.useRealTimers();
});

it('should call callback after delay', () => {
  const callback = jest.fn();
  delayedFunction(callback, 1000);

  jest.advanceTimersByTime(1000);

  expect(callback).toHaveBeenCalled();
});
```

---

## Common Matchers

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
expect(value).toBeCloseTo(0.3, 5); // floating point

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
npx jest

# Watch mode
npx jest --watch

# Run specific file
npx jest helpers.test.ts

# Run tests matching pattern
npx jest --testNamePattern="formatCurrency"

# Coverage report
npx jest --coverage

# Update snapshots
npx jest --updateSnapshot

# Run only changed files
npx jest --onlyChanged

# Verbose output
npx jest --verbose
```

---

## package.json Scripts

```json
{
  "scripts": {
    "test": "jest",
    "test:watch": "jest --watch",
    "test:coverage": "jest --coverage",
    "test:ci": "jest --ci --coverage --maxWorkers=2"
  }
}
```

---

## Example: Testing helpers.ts with Jest

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

## Example: Testing plugin.ts with Jest

```typescript
// plugin.ts
export interface PluginContext {
  config: Record<string, unknown>;
  logger: { info: (msg: string) => void; error: (msg: string) => void };
}

export async function initializePlugin(ctx: PluginContext): Promise<boolean> {
  try {
    ctx.logger.info('Plugin initializing...');
    // Simulate async initialization
    await new Promise((resolve) => setTimeout(resolve, 100));
    ctx.logger.info('Plugin initialized');
    return true;
  } catch (error) {
    ctx.logger.error('Plugin initialization failed');
    return false;
  }
}

// plugin.test.ts
import { initializePlugin, PluginContext } from './plugin';

describe('initializePlugin', () => {
  let mockContext: PluginContext;

  beforeEach(() => {
    jest.useFakeTimers();
    mockContext = {
      config: {},
      logger: {
        info: jest.fn(),
        error: jest.fn(),
      },
    };
  });

  afterEach(() => {
    jest.useRealTimers();
  });

  it('should log initialization messages', async () => {
    const promise = initializePlugin(mockContext);
    jest.advanceTimersByTime(100);
    await promise;

    expect(mockContext.logger.info).toHaveBeenCalledWith('Plugin initializing...');
    expect(mockContext.logger.info).toHaveBeenCalledWith('Plugin initialized');
  });

  it('should return true on success', async () => {
    const promise = initializePlugin(mockContext);
    jest.advanceTimersByTime(100);
    const result = await promise;

    expect(result).toBe(true);
  });

  it('should not call error logger on success', async () => {
    const promise = initializePlugin(mockContext);
    jest.advanceTimersByTime(100);
    await promise;

    expect(mockContext.logger.error).not.toHaveBeenCalled();
  });
});
```
