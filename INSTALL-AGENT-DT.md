# Install `agent` and `dt` (minimal per-user) 🔧

Keep it tiny — just add one snippet to your shell startup (no sudo, no symlinks).

Add this to your `~/.zshrc` or `~/.bashrc` (copy-paste):

```sh
# Load local templates scripts (agent, dt)
export TEMPLATES_DIR="$HOME/workspace/templates"

if [ -d "$TEMPLATES_DIR" ]; then
  # Make agent/dt runnable from anywhere by adding their directories to PATH
  export PATH="$TEMPLATES_DIR/agents:$TEMPLATES_DIR/docker-templates:$PATH"

  # Optional: helper functions that ensure executables are used directly
  agent() { "${TEMPLATES_DIR}/agents/agent" "$@"; }
  dt()    { "${TEMPLATES_DIR}/docker-templates/dt" "$@"; }
fi
```

Then reload your shell: `source ~/.zshrc` or `source ~/.bashrc`.

Notes & tips 💡

- If your repo is elsewhere, change `TEMPLATES_DIR` to the correct path.
- This approach mirrors how tools like `nvm` expose functionality via shell startup code (no install step required).
- If you prefer a single file to source, you can instead add `source "$TEMPLATES_DIR/agents/agent"` — but the `PATH` or functions approach avoids executing code at shell startup.

Testing:

- After reloading your shell, run `agent init` and `dt init --help` to verify.
- You can still run the tests locally: `./agents/test-agent.sh`.

If you'd like, I can add an `install.sh` that appends this snippet to `~/.zshrc` automatically (with a confirmation prompt). Would you like that? 
