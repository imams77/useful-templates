# Agent Skills

A collection of skill templates for AI coding agents, focused on testing and best practices.

## Available Skills

| Skill | Description | Files |
| ----- | ----------- | ----- |
| `react` | Unit testing for React components and hooks | [unit-testing-react/](./unit-testing-react/) |
| `typescript` | Unit testing for TypeScript files | [unit-testing-typescript/](./unit-testing-typescript/) |

---

## Usage

### Initialize a Skill in Your Project

```bash
# Copy React testing skills to current directory
agent-skills init react

# Copy TypeScript testing skills to current directory
agent-skills init typescript

# Overwrite existing skills (force mode)
agent-skills init react --force
```

### From Anywhere

Add the script to your PATH or use the full path:

```bash
# Using full path
/path/to/templates/agent-skills/agent-skills init react

# Or add to PATH (add to your ~/.bashrc or ~/.zshrc)
export PATH="$PATH:/path/to/templates/agent-skills"
agent-skills init typescript
```

---

## Skill Details

### 1. Unit Testing React (`react`)

**Scope:** React components (`.tsx`) and hooks (`.ts`, `.tsx`)

**Frameworks supported:**
- Jest + React Testing Library
- Vitest + React Testing Library

**What you get:**
- `SKILLS.md` - Core React testing principles (query priority, user behavior testing, hook patterns)
- `JEST.md` - Jest configuration, component/hook testing, mocking patterns
- `VITEST.md` - Vitest configuration, same patterns with Vitest API

**Use when:**
- Testing React components
- Testing custom React hooks
- Testing with React Context
- Need form, async, or interaction testing patterns

```bash
agent-skills init react
cd unit-testing-react/
cat SKILLS.md  # Read core principles
cat JEST.md    # Or use Jest-specific guide
```

---

### 2. Unit Testing TypeScript (`typescript`)

**Scope:** TypeScript source files (`.ts`) like `helpers.ts`, `plugin.ts`, `utils.ts`

**Frameworks supported:**
- Jest
- Vitest

**What you get:**
- `SKILLS.md` - Framework-agnostic testing principles (AAA pattern, coverage targets)
- `JEST.md` - Jest configuration, mocking, timers, CLI commands
- `VITEST.md` - Vitest configuration, `vi` API, comparison table

**Use when:**
- Testing pure functions
- Testing utility modules
- Testing plugins or libraries
- Need mocking patterns for external dependencies

```bash
agent-skills init typescript
cd unit-testing-typescript/
cat SKILLS.md   # Read core principles
cat VITEST.md   # Or use Vitest-specific guide
```

---

## File Structure After Init

### React Skills

```
your-project/
└── unit-testing-react/
    ├── SKILLS.md    # Core principles
    ├── JEST.md      # Jest + RTL setup
    └── VITEST.md    # Vitest + RTL setup
```

### TypeScript Skills

```
your-project/
└── unit-testing-typescript/
    ├── SKILLS.md    # Core principles
    ├── JEST.md      # Jest for TS
    └── VITEST.md    # Vitest for TS
```

---

## Command Reference

```bash
# Show help
agent-skills --help
agent-skills init --help

# Initialize skills
agent-skills init react
agent-skills init typescript

# Force overwrite
agent-skills init react --force
agent-skills init typescript -f
```

---

## Development

### Adding a New Skill

1. Create a new folder in `agent-skills/`:
   ```bash
   mkdir agent-skills/new-skill-name
   ```

2. Add skill documentation (at minimum `SKILLS.md`)

3. Update the `agent-skills` script to map the skill name:
   ```bash
   case "$skill" in
     # ... existing skills ...
     new-skill)
       src_dir="$SCRIPT_DIR/new-skill-name"
       target_dir="$PWD/new-skill-name"
       ;;
   esac
   ```

4. Update this README with the new skill

5. Test it:
   ```bash
   agent-skills init new-skill
   ```

---

## Contributing

Skills should follow this structure:

- **SKILLS.md** - Framework-agnostic core principles and patterns
- **JEST.md** (optional) - Jest-specific configuration and examples
- **VITEST.md** (optional) - Vitest-specific configuration and examples
- Additional framework guides as needed

**Principles:**
- Keep framework-specific details separate
- Provide complete, copy-paste ready configs
- Include real-world examples
- Focus on practical patterns over theory
- DO/DON'T lists for quick reference
