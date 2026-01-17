# Install `agent` and `dt` (minimal per-user) 🔧

Keep it tiny — just add one snippet to your shell startup (no sudo, no symlinks).

Add this to your `~/.zshrc` or `~/.bashrc` (copy-paste):

```sh
# Load local templates scripts (agent, dt, dockerphp)
export TEMPLATES_DIR="$HOME/workspace/templates"

if [ -d "$TEMPLATES_DIR" ]; then
  # Make agent/dt/dockerphp runnable from anywhere by adding their directories to PATH
  export PATH="$TEMPLATES_DIR/agents:$TEMPLATES_DIR/docker-templates:$TEMPLATES_DIR/docker-services/php/bin:$PATH"

  # Optional: helper functions that ensure executables are used directly
  agent() { "${TEMPLATES_DIR}/agents/agent" "$@"; }
  dt()    { "${TEMPLATES_DIR}/docker-templates/dt" "$@"; }
  dockerphp() { "${TEMPLATES_DIR}/docker-services/php/bin/dockerphp" "$@"; }

fi
```

Reload your shell: `source ~/.zshrc` or `source ~/.bashrc`.  
