## Installation

### Clone the repo
```
git clone git@github.com:imams77/useful-templates.git ~/.useful-templates
```

### Copy to ./zshrc or ./bashrc

make sure the `TEMPLATES_DIR` variable matched the repository directory
```
# template
export TEMPLATES_DIR="$HOME/.useful-templates"

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

## Usage

### Copilot Agent
Generates copilot instruction file in `[directory]/.github/copilot-instructions.md`
```
agent init
```

### docker template (dt)
Generate docker-compose file for wordpress, mysql, and postgres projects. You need to install docker on your machine

##### Available options
<img width="2529" height="805" alt="image" src="https://github.com/user-attachments/assets/da4a98f0-fbb8-415b-8d2b-cb424ad3da2c" />
