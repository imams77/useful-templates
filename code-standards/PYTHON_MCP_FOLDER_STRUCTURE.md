# Python MCP Server Folder Structure

A minimal folder structure for Python MCP servers using [FastMCP](https://gofastmcp.com).

---

## Overview

```
project-root/
├── app/
│   ├── __init__.py
│   ├── tools/            # MCP tools — one file per tool or group
│   ├── resources/        # MCP resources and resource templates
│   ├── prompts/          # MCP prompt templates
│   ├── services/         # Core business logic used by tools
│   └── utils/            # General-purpose helpers (logger, http, etc.)
├── tests/                # Test files
├── .env.example
├── server.py             # FastMCP server instance and entry point
├── README.md
└── pyproject.toml
```

---

## Directory Purposes

| Directory     | What goes here                                                   |
| ------------- | ---------------------------------------------------------------- |
| `tools/`      | Functions decorated with `@mcp.tool`. One file per tool/group.   |
| `resources/`  | Functions decorated with `@mcp.resource`. Static or templated.   |
| `prompts/`    | Functions decorated with `@mcp.prompt`. Reusable prompt templates. |
| `services/`   | Business logic that tools delegate to. No MCP decorators here.   |
| `utils/`      | Shared helpers — HTTP clients, logger setup, file I/O.           |
| `tests/`      | Test files using `fastmcp.utilities.tests` or pytest.            |

---

## Conventions

| Principle                      | Description                                                              |
| ------------------------------ | ------------------------------------------------------------------------ |
| **Tools are thin**             | Parse input, call a service, return output. Keep business logic in `services/`. |
| **One server instance**        | Create `FastMCP` in `server.py`, import and register tools from `tools/`. |
| **Docstrings are descriptions**| FastMCP uses function docstrings as tool/resource descriptions for LLMs. Write them clearly. |
| **Type hints everywhere**      | FastMCP generates schemas from type hints. Always annotate parameters and return types. |
| **Config from environment**    | Use `.env` and `os.getenv()`. Never hardcode secrets or API keys.        |
| **Tests mirror source**        | Test file names match the module they cover: `test_{module}.py`.         |