# Unit Testing React Skills

## Scope

This skill applies to React files (`.tsx`, `.ts`), including:

- **Components:** `Button.tsx`, `UserCard.tsx`, `Modal.tsx`
- **Hooks:** `useAuth.ts`, `useFetch.ts`, `useLocalStorage.ts`
- **Context:** `AuthContext.tsx`, `ThemeProvider.tsx`

**Excludes:** Test files (`*.test.tsx`, `*.spec.tsx`), type declarations (`*.d.ts`), configuration files.

---

## Framework-Specific Guides

| Framework | Guide | When to Use |
| --------- | ----- | ----------- |
| Jest | [JEST.md](./JEST.md) | CRA, Next.js, established React projects |
| Vitest | [VITEST.md](./VITEST.md) | Vite-based React projects, faster execution |

Both use **React Testing Library** as the testing utility.

---

## Core Principles

### 1. Test User Behavior, Not Implementation

```typescript
// ❌ BAD: Testing implementation details
expect(component.state.isOpen).toBe(true);
expect(wrapper.instance().handleClick).toHaveBeenCalled();

// ✅ GOOD: Testing user-visible behavior
expect(screen.getByRole('dialog')).toBeInTheDocument();
expect(screen.getByText('Success')).toBeVisible();
```

### 2. Query Priority

Use queries in this order of preference:

| Priority | Query | When to Use |
| -------- | ----- | ----------- |
| 1 | `getByRole` | Accessible elements (buttons, headings, etc.) |
| 2 | `getByLabelText` | Form fields |
| 3 | `getByPlaceholderText` | Inputs with placeholders |
| 4 | `getByText` | Non-interactive elements |
| 5 | `getByDisplayValue` | Current value of form elements |
| 6 | `getByAltText` | Images |
| 7 | `getByTitle` | Elements with title attribute |
| 8 | `getByTestId` | Last resort only |

### 3. Async Testing

Always use `findBy` or `waitFor` for async operations:

```typescript
// Element appears after async operation
const element = await screen.findByText('Loaded');

// Wait for condition
await waitFor(() => {
  expect(screen.getByText('Success')).toBeInTheDocument();
});
```

---

## Component Testing Guidelines

### What to Test

| Aspect | Example |
| ------ | ------- |
| Rendering | Component renders without crashing |
| Props | Different props produce correct output |
| User interactions | Click, type, submit work correctly |
| Conditional rendering | Shows/hides based on state or props |
| Error states | Displays error messages appropriately |
| Loading states | Shows loading indicators |
| Accessibility | ARIA attributes, keyboard navigation |

### What NOT to Test

- Internal state values directly
- Implementation details (method names, internal variables)
- Third-party library internals
- CSS styling (use visual regression tools instead)

---

## Hook Testing Guidelines

### When to Test Hooks Directly

- **Complex state logic** with multiple state transitions
- **Reusable hooks** shared across components
- **Hooks with side effects** (API calls, subscriptions)

### When NOT to Test Hooks Directly

- Simple hooks better tested through component behavior
- Hooks that are trivial wrappers around React hooks

### Testing Pattern

```typescript
import { renderHook, act } from '@testing-library/react';
import { useCounter } from './useCounter';

describe('useCounter', () => {
  it('should initialize with default value', () => {
    const { result } = renderHook(() => useCounter());
    expect(result.current.count).toBe(0);
  });

  it('should increment count', () => {
    const { result } = renderHook(() => useCounter());
    
    act(() => {
      result.current.increment();
    });
    
    expect(result.current.count).toBe(1);
  });

  it('should accept initial value', () => {
    const { result } = renderHook(() => useCounter(10));
    expect(result.current.count).toBe(10);
  });
});
```

---

## Best Practices

### DO

- ✅ Test from the user's perspective
- ✅ Use accessible queries (`getByRole`, `getByLabelText`)
- ✅ Test component integration, not isolation
- ✅ Use `userEvent` over `fireEvent` for realistic interactions
- ✅ Wait for async operations properly
- ✅ Test error boundaries and edge cases
- ✅ Keep tests focused on one behavior

### DON'T

- ❌ Test internal state or implementation
- ❌ Use `getByTestId` as first choice
- ❌ Snapshot test everything (use sparingly)
- ❌ Mock everything—prefer integration
- ❌ Test third-party component internals
- ❌ Rely on DOM structure (fragile tests)

---

## Common Patterns

### Testing a Form

```typescript
import { render, screen } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import { LoginForm } from './LoginForm';

describe('LoginForm', () => {
  it('should submit form with entered values', async () => {
    const handleSubmit = /* mock function */;
    const user = userEvent.setup();
    
    render(<LoginForm onSubmit={handleSubmit} />);
    
    await user.type(screen.getByLabelText(/email/i), 'test@example.com');
    await user.type(screen.getByLabelText(/password/i), 'secret123');
    await user.click(screen.getByRole('button', { name: /sign in/i }));
    
    expect(handleSubmit).toHaveBeenCalledWith({
      email: 'test@example.com',
      password: 'secret123',
    });
  });

  it('should show validation error for invalid email', async () => {
    const user = userEvent.setup();
    
    render(<LoginForm onSubmit={() => {}} />);
    
    await user.type(screen.getByLabelText(/email/i), 'invalid');
    await user.click(screen.getByRole('button', { name: /sign in/i }));
    
    expect(screen.getByText(/invalid email/i)).toBeInTheDocument();
  });
});
```

### Testing Conditional Rendering

```typescript
describe('UserProfile', () => {
  it('should show loading state', () => {
    render(<UserProfile isLoading={true} />);
    expect(screen.getByRole('progressbar')).toBeInTheDocument();
  });

  it('should show error state', () => {
    render(<UserProfile error="Failed to load" />);
    expect(screen.getByRole('alert')).toHaveTextContent('Failed to load');
  });

  it('should show user data when loaded', () => {
    render(<UserProfile user={{ name: 'John', email: 'john@test.com' }} />);
    expect(screen.getByText('John')).toBeInTheDocument();
    expect(screen.getByText('john@test.com')).toBeInTheDocument();
  });
});
```

### Testing with Context

```typescript
const renderWithProviders = (ui: React.ReactElement) => {
  return render(
    <ThemeProvider>
      <AuthProvider>
        {ui}
      </AuthProvider>
    </ThemeProvider>
  );
};

it('should use theme from context', () => {
  renderWithProviders(<ThemedButton />);
  expect(screen.getByRole('button')).toHaveClass('dark-theme');
});
```

---

## Coverage Expectations

| Metric | Minimum Target |
| ------ | -------------- |
| Line coverage | 80% |
| Branch coverage | 75% |
| Function coverage | 90% |

**Priority coverage areas:**

1. User-facing components with complex interactions
2. Shared/reusable components
3. Custom hooks with business logic
4. Error handling paths
