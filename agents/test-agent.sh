#!/usr/bin/env bash
set -euo pipefail

# Simple test script for agents/agent
# Verifies: normal copy, abort when exists, force overwrite

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
AGENT="$ROOT_DIR/agent"
SRC="$ROOT_DIR/copilot-instructions.md"

tmpdir="$(mktemp -d)"
orig_backup="$tmpdir/copilot-instructions.orig"
cleanup() {
  if [ -f "$orig_backup" ]; then
    mv "$orig_backup" "$SRC"
  fi
  rm -rf "$tmpdir"
}
trap cleanup EXIT

cd "$tmpdir"

echo "Test: initial copy creates .github/copilot-instructions.md"
$AGENT init
if [ ! -f .github/copilot-instructions.md ]; then
  echo "FAIL: file not copied" >&2
  exit 2
fi

echo "Test: second run fails without --force"
set +e
if $AGENT init >/dev/null 2>&1; then
  echo "FAIL: second run should have failed when file exists" >&2
  exit 3
fi
set -e

# Modify source so we can detect overwrite
cp "$SRC" "$orig_backup"
echo "--- UPDATED---" >> "$SRC"

echo "Test: --force overwrites existing file"
$AGENT init --force
if ! grep -q -- "--- UPDATED---" .github/copilot-instructions.md; then
  echo "FAIL: --force did not overwrite file" >&2
  exit 4
fi

# Test: --gitignore adds entry to .gitignore when present
printf "node_modules/\n" > .gitignore
$AGENT init --force --gitignore
if ! grep -Fqx -- ".github/copilot-instructions.md" .gitignore; then
  echo "FAIL: .gitignore did not receive the entry" >&2
  exit 6
fi
# ensure only one occurrence
count=$(grep -Fxc -- ".github/copilot-instructions.md" .gitignore || true)
if [ "$count" -ne 1 ]; then
  echo "FAIL: duplicate entries in .gitignore" >&2
  exit 7
fi

# Clean up .github and test exit codes
rm -rf .github

# Test: --gitignore when .gitignore missing
echo "Test: --gitignore when .gitignore absent prints guidance"
rm -f .gitignore
out=$($AGENT init --force --gitignore 2>&1)
if ! echo "$out" | grep -q "No .gitignore found"; then
  echo "FAIL: did not print missing .gitignore guidance" >&2
  echo "Output was: $out" >&2
  exit 8
fi
# ensure .gitignore not created
if [ -f .gitignore ]; then
  echo "FAIL: .gitignore should not be created automatically" >&2
  exit 9
fi

# Ensure .github removed before final check
rm -rf .github

# Test when .github missing
echo "Test: works when .github is absent"
$AGENT init
if [ ! -f .github/copilot-instructions.md ]; then
  echo "FAIL: did not create .github dir" >&2
  exit 5
fi

cat <<EOF
All tests passed ✅
EOF
exit 0
