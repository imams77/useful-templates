# Jest + React Testing Library

Jest with React Testing Library is the standard setup for testing React applications, especially with Create React App and Next.js.

**Use Jest when:**

- Using Create React App
- Using Next.js
- Established React projects with Jest already configured
- Need extensive mocking capabilities

---

## Installation

```bash
npm install -D jest @types/jest ts-jest \
  @testing-library/react @testing-library/jest-dom @testing-library/user-event \
  jest-environment-jsdom
```

---

## Configuration

### `jest.config.ts`

```typescript
import type { Config } from 'jest';

const config: Config = {
  preset: 'ts-jest',
  testEnvironment: 'jsdom',
  roots: ['<rootDir>/src'],
  testMatch: ['**/*.test.tsx', '**/*.test.ts', '**/*.spec.tsx', '**/*.spec.ts'],
  moduleFileExtensions: ['ts', 'tsx', 'js', 'jsx', 'json'],
  setupFilesAfterEnv: ['<rootDir>/src/setupTests.ts'],
  moduleNameMapper: {
    '^@/(.*)$': '<rootDir>/src/$1',
    '\\.(css|less|scss|sass)$': 'identity-obj-proxy',
    '\\.(jpg|jpeg|png|gif|svg)$': '<rootDir>/__mocks__/fileMock.ts',
  },
  collectCoverageFrom: [
    'src/**/*.{ts,tsx}',
    '!src/**/*.d.ts',
    '!src/**/*.test.{ts,tsx}',
    '!src/**/index.ts',
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

### `src/setupTests.ts`

```typescript
import '@testing-library/jest-dom';

// Mock window.matchMedia
Object.defineProperty(window, 'matchMedia', {
  writable: true,
  value: jest.fn().mockImplementation((query) => ({
    matches: false,
    media: query,
    onchange: null,
    addListener: jest.fn(),
    removeListener: jest.fn(),
    addEventListener: jest.fn(),
    removeEventListener: jest.fn(),
    dispatchEvent: jest.fn(),
  })),
});

// Mock IntersectionObserver
global.IntersectionObserver = jest.fn().mockImplementation(() => ({
  observe: jest.fn(),
  unobserve: jest.fn(),
  disconnect: jest.fn(),
}));
```

### `__mocks__/fileMock.ts`

```typescript
export default 'test-file-stub';
```

---

## Testing Components

### Basic Component Test

```typescript
import { render, screen } from '@testing-library/react';
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
import { ContactForm } from './ContactForm';

describe('ContactForm', () => {
  it('should submit form with entered data', async () => {
    const handleSubmit = jest.fn();
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
    render(<ContactForm onSubmit={jest.fn()} />);

    await user.click(screen.getByRole('button', { name: /submit/i }));

    expect(screen.getByText(/name is required/i)).toBeInTheDocument();
    expect(screen.getByText(/email is required/i)).toBeInTheDocument();
  });
});
```

### Testing Async Components

```typescript
import { render, screen, waitFor } from '@testing-library/react';
import { UserList } from './UserList';
import { fetchUsers } from './api';

jest.mock('./api');
const mockFetchUsers = fetchUsers as jest.MockedFunction<typeof fetchUsers>;

describe('UserList', () => {
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
import { useFetch } from './useFetch';

// Mock fetch
global.fetch = jest.fn();
const mockFetch = fetch as jest.MockedFunction<typeof fetch>;

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
jest.mock('./api', () => ({
  fetchData: jest.fn().mockResolvedValue({ data: 'mocked' }),
}));
```

### Mock Component

```typescript
jest.mock('./HeavyComponent', () => ({
  HeavyComponent: () => <div data-testid="mock-heavy">Mocked</div>,
}));
```

### Mock Router (React Router)

```typescript
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

### Mock Next.js Router

```typescript
import { useRouter } from 'next/router';

jest.mock('next/router', () => ({
  useRouter: jest.fn(),
}));

const mockRouter = useRouter as jest.MockedFunction<typeof useRouter>;

beforeEach(() => {
  mockRouter.mockReturnValue({
    pathname: '/',
    query: {},
    push: jest.fn(),
    replace: jest.fn(),
    back: jest.fn(),
  } as any);
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
npx jest Button.test.tsx

# Coverage report
npx jest --coverage

# Update snapshots
npx jest --updateSnapshot

# Run tests matching pattern
npx jest --testNamePattern="should render"
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
