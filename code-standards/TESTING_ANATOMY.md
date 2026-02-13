# Testing Anatomy

A quick guide for creating unit tests in this project.

## Folder Structure

```
your-module/
├── __fixtures__/           # Shared test data
│   └── index.ts
├── domain/
│   ├── __tests__/          # Tests live next to the code
│   │   └── your-logic.test.ts
│   └── your-logic.ts
├── transform/
│   ├── __tests__/
│   │   └── your-transform.test.ts
│   └── your-transform.ts
└── hooks/
    ├── __tests__/
    │   └── your-hook.test.ts
    └── your-hook.ts
```

## Step-by-Step Guide

### 1. Create Fixtures (if needed)

Create `__fixtures__/index.ts` in your module to share test data.

```ts
// __fixtures__/index.ts

// Factory function - creates single entity with defaults
export const createUser = (overrides = {}) => ({
  id: 'user-1',
  name: 'John Doe',
  ...overrides,
});

// Pre-built collections - common scenarios
export const fixtures = {
  users: {
    default: [
      createUser({ id: 'u1', name: 'User 1' }),
      createUser({ id: 'u2', name: 'User 2' }),
    ],
    single: createUser({ id: 'u1', name: 'User 1' }),
  },
  // Add more as needed
};
```

### 2. Create Test File

Create `__tests__/your-file.test.ts` next to the file you're testing.

```ts
// __tests__/your-logic.test.ts
import { describe, it, expect } from 'vitest';
import { fixtures, createUser } from '../../__fixtures__';
import { yourFunction } from '../your-logic';

describe('yourFunction', () => {
  it('does something expected', () => {
    const result = yourFunction(fixtures.users.single);
    expect(result).toBe('expected value');
  });

  it('handles edge case', () => {
    const result = yourFunction(null);
    expect(result).toBeNull();
  });
});
```

### 3. Run Tests

```bash
pnpm test                    # Run all tests
pnpm test your-file          # Run specific file
pnpm test --watch            # Watch mode
```

## Examples

### Example 1: Testing Pure Logic

```ts
// domain/__tests__/calculator.test.ts
import { describe, it, expect } from 'vitest';
import { add, multiply } from '../calculator';

describe('calculator', () => {
  describe('add', () => {
    it('adds two numbers', () => {
      expect(add(2, 3)).toBe(5);
    });

    it('returns 0 for empty input', () => {
      expect(add()).toBe(0);
    });
  });
});
```

### Example 2: Testing Transform Functions

```ts
// transform/__tests__/user.transform.test.ts
import { describe, it, expect } from 'vitest';
import { fixtures, createUser } from '../../__fixtures__';
import { toUserPayload } from '../user.transform';

describe('toUserPayload', () => {
  it('transforms user to API payload', () => {
    const result = toUserPayload(fixtures.users.single);

    expect(result).toEqual({
      user_id: 'u1',
      full_name: 'User 1',
    });
  });

  it('handles empty array', () => {
    const result = toUserPayload([]);
    expect(result).toEqual([]);
  });
});
```

## Checklist

- [ ] Test file is in `__tests__/` folder next to source
- [ ] File name ends with `.test.ts`
- [ ] Import `describe`, `it`, `expect` from `vitest`
- [ ] Use fixtures for shared test data
- [ ] Each `it` block tests ONE behavior
- [ ] Test names describe expected behavior