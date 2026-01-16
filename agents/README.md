# agent — GitHub Copilot instructions helper 🔧

Small POSIX shell CLI to copy `copilot-instructions.md` into your project's `.github/` directory.

## Run (no npm required) 💡

- Run directly from the repo:

  ./agents/agent init

- Or make it globally available:

  chmod +x ./agents/agent
  sudo ln -s "$(pwd)/agents/agent" /usr/local/bin/agent

## Usage

  agent init [--force|-f]

Examples:
  agent init                 # create .github/copilot-instructions.md (abort if exists)
  agent init --force         # overwrite existing file
  agent init --gitignore     # add entry to .gitignore if it exists

The command aborts if `.github/copilot-instructions.md` already exists unless you pass `--force`/`-f`. If you pass `--gitignore`/`-g` and the project has a `.gitignore` file, the CLI will append the entry `.github/copilot-instructions.md` (no duplicate entries). If `.gitignore` is absent, the CLI will print a short note explaining how to add the entry later.

## Testing ✅

A simple POSIX test script verifies basic behaviors: initial copy, abort when target exists, and `--force` overwrites.

Run tests locally:

  chmod +x ./agents/test-agent.sh
  ./agents/test-agent.sh

The test script runs in a temporary directory and leaves your repository unchanged (it only modifies `agents/copilot-instructions.md` by appending a small marker during one test, which is reverted when the test completes).
