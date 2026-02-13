# Vitest + React Testing Library

Vitest with React Testing Library is the modern, fast setup for Vite-based React projects.

**Use Vitest when:**

- Using Vite as your build tool
- Building with React + Vite template
- Need faster test execution with native ESM
- Using frameworks like Remix (Vite mode)

---

## Installation

```bash
npm install -D vitest @vitest/coverage-v8 jsdom \
  @testing-library/react @testing-library/jest-dom @testing-library/user-event
```

---

## Configuration

### `vite.config.ts` (or `vitest.config.ts`)

```typescript
/// <reference types="vitest" />
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
  plugins: [react()],
  test: {
    globals: true,
    environment: 'jsdom',
    setupFiles: ['./src/setupTests.ts'],
    include: ['**/*.test.tsx', '**/*.test.ts', '**/*.spec.tsx', '**/*.spec.ts'],
    exclude: ['node_modules', 'dist'],
    css: true,
    coverage: {
      provider: 'v8',
      reporter: ['text', 'json', 'html'],
      include: ['src/**/*.{ts,tsx}'],
      exclude: [
        'src/**/*.d.ts',
        'src/**/*.test.{ts,tsx}',
        'src/**/index.ts',
      ],
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

### `src/setupTests.ts`

```typescript
import '@testing-library/jest-dom/vitest';
import { vi } from 'vitest';

// Mock window.matchMedia
Object.defineProperty(window, 'matchMedia', {
  writable: true,
  value: vi.fn().mockImplementation((query) => ({
    matches: false,
    media: query,
    onchange: null,
    addListener: vi.fn(),
    removeListener: vi.fn(),
    addEventListener: vi.fn(),
    removeEventListener: vi.fn(),
    dispatchEvent: vi.fn(),
  })),
});

// Mock IntersectionObserver
global.IntersectionObserver = vi.fn().mockImplementation(() => ({
  observe: vi.fn(),
  unobserve: vi.fn(),
  disconnect: vi.fn(),
}));
```

### `tsconfig.json` additions

```json
{
  "compilerOptions": {
    "types": ["vitest/globals", "node", "@testing-library/jest-dom"]
  }
}
```

---

## Testing Components

### Basic Component Test

```typescript
import { render, screen } from '@testing-library/react';
import { describe, it, expect } from 'vitest';
import { Button } from './Button';

describe('Button', () => {
  it('should render with text', () => {
    render(<Button>Click me</Button>);
    expect(screen.getByRole('button', { name: /click me/i })).toBeInTheDocument();
  });

  it('should be disabled when disabled prop is true', () => {
    render(<Button disabled>Click me</Button>);
    expect(screen.getByRole('button')).toBeDisabled();
  });

  it('should apply variant class', () => {
    render(<Button variant="primary">Click me</Button>);
    expect(screen.getByRole('button')).toHaveClass('btn-primary');
  });
});
```

### Testing User Interactions

```typescript
import { render, screen } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import { describe, it, expect } from 'vitest';
import { Counter } from './Counter';

describe('Counter', () => {
  it('should increment count when button is clicked', async () => {
    const user = userEvent.setup();
    render(<Counter />);

    expect(screen.getByText('Count: 0')).toBeInTheDocument();

    await user.click(screen.getByRole('button', { name: /increment/i }));

    expect(screen.getByText('Count: 1')).toBeInTheDocument();
  });

  it('should decrement count when decrement button is clicked', async () => {
    const user = userEvent.setup();
    render(<Counter initialCount={5} />);

    await user.click(screen.getByRole('button', { name: /decrement/i }));

    expect(screen.getByText('Count: 4')).toBeInTheDocument();
  });
});
```

### Testing Forms

```typescript
import { render, screen } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import { describe, it, expect, vi } from 'vitest';
import { ContactForm } from './ContactForm';

describe('ContactForm', () => {
  it('should submit form with entered data', async () => {
    const handleSubmit = vi.fn();
    const user = userEvent.setup();

    render(<ContactForm onSubmit={handleSubmit} />);

    await user.type(screen.getByLabelText(/name/i), 'John Doe');
    await user.type(screen.getByLabelText(/email/i), 'john@example.com');
    await user.type(screen.getByLabelText(/message/i), 'Hello there!');
    await user.click(screen.getByRole('button', { name: /submit/i }));

    expect(handleSubmit).toHaveBeenCalledWith({
      name: 'John Doe',
      email: 'john@example.com',
      message: 'Hello there!',
    });
  });

  it('should show required field errors', async () => {
    const user = userEvent.setup();
    render(<ContactForm onSubmit={vi.fn()} />);

    await user.click(screen.getByRole('button', { name: /submit/i }));

    expect(screen.getByText(/name is required/i)).toBeInTheDocument();
    expect(screen.getByText(/email is required/i)).toBeInTheDocument();
  });
});
```

### Testing Async Components

```typescript
import { render, screen, waitFor } from '@testing-library/react';
import { describe, it, expect, vi, beforeEach } from 'vitest';
import { UserList } from './UserList';
import { fetchUsers } from './api';

vi.mock('./api');
const mockFetchUsers = vi.mocked(fetchUsers);

describe('UserList', () => {
  beforeEach(() => {
    vi.clearAllMocks();
  });

  it('should show loading state initially', () => {
    mockFetchUsers.mockReturnValue(new Promise(() => {})); // Never resolves
    render(<UserList />);

    expect(screen.getByRole('progressbar')).toBeInTheDocument();
  });

  it('should render users after loading', async () => {
    mockFetchUsers.mockResolvedValue([
      { id: 1, name: 'Alice' },
      { id: 2, name: 'Bob' },
    ]);

    render(<UserList />);

    expect(await screen.findByText('Alice')).toBeInTheDocument();
    expect(screen.getByText('Bob')).toBeInTheDocument();
  });

  it('should show error message on failure', async () => {
    mockFetchUsers.mockRejectedValue(new Error('Network error'));

    render(<UserList />);

    expect(await screen.findByRole('alert')).toHaveTextContent('Failed to load users');
  });
});
```

---

## Testing Hooks

### Basic Hook Test

```typescript
import { renderHook, act } from '@testing-library/react';
import { describe, it, expect } from 'vitest';
import { useToggle } from './useToggle';

describe('useToggle', () => {
  it('should initialize with false by default', () => {
    const { result } = renderHook(() => useToggle());
    expect(result.current.isOn).toBe(false);
  });

  it('should initialize with provided value', () => {
    const { result } = renderHook(() => useToggle(true));
    expect(result.current.isOn).toBe(true);
  });

  it('should toggle value', () => {
    const { result } = renderHook(() => useToggle());

    act(() => {
      result.current.toggle();
    });

    expect(result.current.isOn).toBe(true);

    act(() => {
      result.current.toggle();
    });

    expect(result.current.isOn).toBe(false);
  });

  it('should set value directly', () => {
    const { result } = renderHook(() => useToggle());

    act(() => {
      result.current.setOn();
    });
    expect(result.current.isOn).toBe(true);

    act(() => {
      result.current.setOff();
    });
    expect(result.current.isOn).toBe(false);
  });
});
```

### Testing Async Hooks

```typescript
import { renderHook, waitFor } from '@testing-library/react';
import { describe, it, expect, vi, beforeEach } from 'vitest';
import { useFetch } from './useFetch';

// Mock fetch
const mockFetch = vi.fn();
global.fetch = mockFetch;

describe('useFetch', () => {
  beforeEach(() => {
    mockFetch.mockClear();
  });

  it('should return loading state initially', () => {
    mockFetch.mockReturnValue(new Promise(() => {}));

    const { result } = renderHook(() => useFetch('/api/data'));

    expect(result.current.isLoading).toBe(true);
    expect(result.current.data).toBeNull();
    expect(result.current.error).toBeNull();
  });

  it('should return data on success', async () => {
    mockFetch.mockResolvedValue({
      ok: true,
      json: () => Promise.resolve({ name: 'Test' }),
    } as Response);

    const { result } = renderHook(() => useFetch('/api/data'));

    await waitFor(() => {
      expect(result.current.isLoading).toBe(false);
    });

    expect(result.current.data).toEqual({ name: 'Test' });
    expect(result.current.error).toBeNull();
  });

  it('should return error on failure', async () => {
    mockFetch.mockRejectedValue(new Error('Network error'));

    const { result } = renderHook(() => useFetch('/api/data'));

    await waitFor(() => {
      expect(result.current.isLoading).toBe(false);
    });

    expect(result.current.data).toBeNull();
    expect(result.current.error).toBe('Network error');
  });
});
```

### Testing Hooks with Context

```typescript
import { renderHook, act } from '@testing-library/react';
import { describe, it, expect } from 'vitest';
import { useAuth, AuthProvider } from './AuthContext';

const wrapper = ({ children }: { children: React.ReactNode }) => (
  <AuthProvider>{children}</AuthProvider>
);

describe('useAuth', () => {
  it('should return null user when not logged in', () => {
    const { result } = renderHook(() => useAuth(), { wrapper });
    expect(result.current.user).toBeNull();
  });

  it('should login user', async () => {
    const { result } = renderHook(() => useAuth(), { wrapper });

    await act(async () => {
      await result.current.login('test@example.com', 'password');
    });

    expect(result.current.user).toEqual({ email: 'test@example.com' });
  });

  it('should logout user', async () => {
    const { result } = renderHook(() => useAuth(), { wrapper });

    await act(async () => {
      await result.current.login('test@example.com', 'password');
    });

    act(() => {
      result.current.logout();
    });

    expect(result.current.user).toBeNull();
  });
});
```

---

## Mocking

### Mock Module

```typescript
import { vi } from 'vitest';

vi.mock('./api', () => ({
  fetchData: vi.fn().mockResolvedValue({ data: 'mocked' }),
}));
```

### Type-Safe Mock

```typescript
import { vi } from 'vitest';
import { fetchData } from './api';

vi.mock('./api');

const mockFetchData = vi.mocked(fetchData);

beforeEach(() => {
  mockFetchData.mockResolvedValue({ data: 'test' });
});
```

### Mock Component

```typescript
import { vi } from 'vitest';

vi.mock('./HeavyComponent', () => ({
  HeavyComponent: () => <div data-testid="mock-heavy">Mocked</div>,
}));
```

### Mock Router (React Router)

```typescript
import { render, screen } from '@testing-library/react';
import { MemoryRouter } from 'react-router-dom';

const renderWithRouter = (ui: React.ReactElement, { route = '/' } = {}) => {
  return render(
    <MemoryRouter initialEntries={[route]}>
      {ui}
    </MemoryRouter>
  );
};

it('should render dashboard on /dashboard route', () => {
  renderWithRouter(<App />, { route: '/dashboard' });
  expect(screen.getByText('Dashboard')).toBeInTheDocument();
});
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

it('should debounce input', async () => {
  const onChange = vi.fn();
  const user = userEvent.setup({ advanceTimers: vi.advanceTimersByTime });

  render(<DebouncedInput onChange={onChange} delay={500} />);

  await user.type(screen.getByRole('textbox'), 'test');
  expect(onChange).not.toHaveBeenCalled();

  vi.advanceTimersByTime(500);
  expect(onChange).toHaveBeenCalledWith('test');
});
```

---

## CLI Commands

```bash
# Run all tests (watch mode by default)
npx vitest

# Run once
npx vitest run

# Run specific file
npx vitest Button.test.tsx

# Coverage report
npx vitest run --coverage

# UI mode (interactive)
npx vitest --ui

# Run tests matching pattern
npx vitest --testNamePattern="should render"

# Watch specific files
npx vitest --watch src/components/
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

## Vitest vs Jest: React Testing Differences

| Feature | Jest | Vitest |
| ------- | ---- | ------ |
| Mock function | `jest.fn()` | `vi.fn()` |
| Mock module | `jest.mock()` | `vi.mock()` |
| Type-safe mock | `as jest.MockedFunction<T>` | `vi.mocked(fn)` |
| Fake timers | `jest.useFakeTimers()` | `vi.useFakeTimers()` |
| Setup file import | `@testing-library/jest-dom` | `@testing-library/jest-dom/vitest` |
| Config file | `jest.config.ts` | `vite.config.ts` or `vitest.config.ts` |
| Watch mode | Opt-in (`--watch`) | Default |
| Coverage provider | Built-in | `@vitest/coverage-v8` |
