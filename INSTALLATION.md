# Install `agent`, `agent-skills`, `dt`, and `dockerphp` (minimal per-user) 🔧

Keep it tiny — just add one snippet to your shell startup (no sudo, no symlinks).

Add this to your `~/.zshrc` or `~/.bashrc` (copy-paste):

```sh
# Load local templates scripts (agent, agent-skills, dt, dockerphp)
export TEMPLATES_DIR="$HOME/workspace/templates"

if [ -d "$TEMPLATES_DIR" ]; then
  # Make all scripts runnable from anywhere by adding their directories to PATH
  export PATH="$TEMPLATES_DIR/agents:$TEMPLATES_DIR/agent-skills:$TEMPLATES_DIR/docker-templates:$TEMPLATES_DIR/docker-services/php/bin:$PATH"

  # Optional: helper functions that ensure executables are used directly
  agent() { "${TEMPLATES_DIR}/agents/agent" "$@"; }
  agent-skills() { "${TEMPLATES_DIR}/agent-skills/agent-skills" "$@"; }
  dt()    { "${TEMPLATES_DIR}/docker-templates/dt" "$@"; }
  dockerphp() { "${TEMPLATES_DIR}/docker-services/php/bin/dockerphp" "$@"; }

fi
```

Reload your shell: `source ~/.zshrc` or `source ~/.bashrc`.

---

## Available Commands

After installation, you can use:

| Command | Description | Example |
| ------- | ----------- | ------- |
| `agent` | Initialize GitHub Copilot instructions | `agent init` |
| `agent-skills` | Copy testing skill templates | `agent-skills init react` |
| `dt` | Initialize Docker templates | `dt init postgres` |
| `dockerphp` | Manage PHP Docker containers | `dockerphp start 8.4` |

---

## Quick Start Examples

```bash
# Setup Copilot instructions for your project
cd my-project/
agent init

# Add React testing skills
agent-skills init react

# Add TypeScript testing skills  
agent-skills init typescript

# Initialize PostgreSQL Docker setup
dt init postgres

# Start PHP 8.4 development server
dockerphp start 8.4
```  
