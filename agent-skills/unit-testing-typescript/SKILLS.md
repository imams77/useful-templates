# Unit Testing TypeScript Skills

## Scope

This skill applies to TypeScript source files (`.ts`), including but not limited to:

- `plugin.ts`
- `helpers.ts`
- `utils.ts`
- `services.ts`
- Any other `.ts` files containing business logic

**Excludes:** Test files (`*.test.ts`, `*.spec.ts`), type declaration files (`*.d.ts`), configuration files.

---

## Framework-Specific Guides

| Framework | Guide | When to Use |
| --------- | ----- | ----------- |
| Jest | [JEST.md](./JEST.md) | Mature ecosystem, extensive mocking, large community |
| Vitest | [VITEST.md](./VITEST.md) | Vite projects, faster execution, ESM-native |

---

## Core Principles

### 1. Test File Naming & Location

- Place test files adjacent to source files: `helpers.ts` → `helpers.test.ts`
- Alternative: Use `__tests__/` folder at the same level
- Use `.test.ts` or `.spec.ts` extension consistently

### 2. Test Structure

```typescript
describe('ModuleName', () => {
  describe('functionName', () => {
    it('should return expected result when given valid input', () => {
      // Arrange
      const input = 'test';
      
      // Act
      const result = functionName(input);
      
      // Assert
      expect(result).toBe('expected');
    });

    it('should throw error when given invalid input', () => {
      expect(() => functionName(null)).toThrow();
    });
  });
});
```

### 3. Arrange-Act-Assert (AAA) Pattern

Every test should follow:

1. **Arrange** - Set up test data and dependencies
2. **Act** - Execute the function under test
3. **Assert** - Verify the expected outcome

---

## Testing Guidelines

### Function Testing

| Scenario | What to Test |
| -------- | ------------ |
| Happy path | Valid inputs produce expected outputs |
| Edge cases | Empty arrays, null, undefined, boundary values |
| Error handling | Invalid inputs throw appropriate errors |
| Type coercion | Ensure type safety is maintained |

### Async Function Testing

```typescript
it('should handle async operations', async () => {
  const result = await asyncFunction();
  expect(result).toBeDefined();
});

it('should reject with error on failure', async () => {
  await expect(asyncFunction()).rejects.toThrow('Expected error');
});
```

---

## Best Practices

### DO

- ✅ Write tests before or alongside code (TDD/concurrent)
- ✅ Test one behavior per test case
- ✅ Use descriptive test names that explain the scenario
- ✅ Keep tests independent and isolated
- ✅ Mock external dependencies (API calls, file system, databases)
- ✅ Test both success and failure paths
- ✅ Use `beforeEach` for common setup, `afterEach` for cleanup

### DON'T

- ❌ Test implementation details (private methods, internal state)
- ❌ Write tests that depend on execution order
- ❌ Use real network calls or database connections
- ❌ Ignore flaky tests—fix them immediately
- ❌ Over-mock—if everything is mocked, you're testing mocks

---

## Coverage Expectations

| Metric | Minimum Target |
| ------ | -------------- |
| Line coverage | 80% |
| Branch coverage | 75% |
| Function coverage | 90% |

Focus on **meaningful coverage**, not just hitting numbers. Critical business logic should have near 100% coverage.

---

## Common Testing Patterns

### Testing Pure Functions (helpers.ts)

```typescript
// helpers.ts
export function formatCurrency(amount: number): string {
  return `$${amount.toFixed(2)}`;
}

// helpers.test.ts
describe('formatCurrency', () => {
  it('should format positive numbers', () => {
    expect(formatCurrency(10)).toBe('$10.00');
  });

  it('should handle decimals', () => {
    expect(formatCurrency(10.5)).toBe('$10.50');
  });

  it('should handle zero', () => {
    expect(formatCurrency(0)).toBe('$0.00');
  });

  it('should handle negative numbers', () => {
    expect(formatCurrency(-5)).toBe('$-5.00');
  });
});
```

### Testing Plugin Functions (plugin.ts)

```typescript
// plugin.ts
export interface PluginContext {
  config: Record<string, unknown>;
  logger: { info: (msg: string) => void };
}

export function initializePlugin(ctx: PluginContext): boolean {
  ctx.logger.info('Plugin initialized');
  return true;
}

// plugin.test.ts
describe('initializePlugin', () => {
  let mockContext: PluginContext;

  beforeEach(() => {
    mockContext = {
      config: {},
      logger: { info: /* framework mock function */ },
    };
  });

  it('should log initialization message', () => {
    initializePlugin(mockContext);
    expect(mockContext.logger.info).toHaveBeenCalledWith('Plugin initialized');
  });

  it('should return true on successful init', () => {
    expect(initializePlugin(mockContext)).toBe(true);
  });
});
```

> **Note:** See [JEST.md](./JEST.md) or [VITEST.md](./VITEST.md) for framework-specific mock syntax.
