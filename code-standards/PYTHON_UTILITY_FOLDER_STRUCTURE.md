# Python Utility App Folder Structure

A minimal folder structure for Python utility/CLI applications.

---

## Overview

```
project-root/
├── app/
│   ├── __init__.py
│   ├── commands/       # CLI commands — one file per command
│   ├── config/         # Settings and environment loading
│   ├── services/       # Core business logic
│   └── utils/          # General-purpose helpers (logger, file I/O, etc.)
├── tests/              # Test files
├── .env.example
├── main.py             # Entry point
├── README.md
└── requirements.txt
```

---

## Directory Purposes

| Directory    | What goes here                                              |
| ------------ | ----------------------------------------------------------- |
| `commands/`  | One file per command or task. Thin — delegates to services. |
| `config/`    | Environment variable loading, app settings.                 |
| `services/`  | Core logic. One service per concern.                        |
| `utils/`     | Shared tools — logger, file helpers, retry wrappers.        |
| `tests/`     | Test files mirroring `app/` structure.                      |

---

## Conventions

| Principle                    | Description                                                     |
| ---------------------------- | --------------------------------------------------------------- |
| **Flat over nested**         | One level inside `app/` is usually enough.                      |
| **Commands are thin**        | Parse input, call a service, return output. No business logic.  |
| **Config from environment**  | Use `.env` and `os.getenv()`. Never hardcode secrets.           |
| **Tests mirror source**      | Test file names match the module they cover: `test_{module}.py` |