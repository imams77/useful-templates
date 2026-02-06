#!/bin/bash

PROJECT_DIR="${1:-.}"  # Default to current dir if no arg

# Validate project dir
if [[ ! -d "$PROJECT_DIR" ]]; then
  echo "Error: Project directory '$PROJECT_DIR' not found"
  exit 1
fi

# Load .env from script location (not project)
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
set -a
source "$SCRIPT_DIR/.env" 2>/dev/null || { echo "Error: .env not found in $SCRIPT_DIR"; exit 1; }
set +a

# Run scan on target project
docker run --rm \
  -e SONAR_TOKEN="$SONAR_TOKEN" \
  -e SONAR_PROJECT_KEY="$SONAR_PROJECT_KEY" \
  -e SONAR_HOST_URL="${SONAR_HOST_URL:-http://localhost:9000}" \
  -v "$PROJECT_DIR":/usr/src \
  sonarsource/sonar-scanner-cli

echo "Scan complete for $PROJECT_DIR"
