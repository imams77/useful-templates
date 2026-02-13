# PostgreSQL — Installation & Multi-instance Notes 🐘

## Install & Run

### Prerequisites

Docker & Docker Compose

### Start service

```bash
cd /path/to/workspace/templates/docker-db/postgres
docker compose -f docker-compose-postgres.yml up -d
```

### Verify & connect

```bash
docker compose -f docker-compose-postgres.yml ps
# Connect with psql:
psql "host=127.0.0.1 port=5432 user=postgres password=postgres dbname=postgres"
```

### Example: namespaced stack

```bash
docker compose -p pg_staging -f docker-compose-postgres.yml up -d
```

### Using a per-service `.env`

If you keep a `.env` beside the compose file (`postgres/.env`) either:

- Change directory and run compose:

```bash
cd postgres
docker compose -f docker-compose-postgres.yml up -d
```

- Or pass it explicitly without changing directories:

```bash
docker compose --env-file postgres/.env -f docker-compose-postgres.yml up -d
```

## Files of interest

- `init-db.sql` — initialization SQL executed on first startup
- `pgadmin-servers.json` — pre-config for pgAdmin (if used)

## Adjustments for running multiple instances

- Change host port mapping (e.g. `5432:5432` → `5433:5432`).
- Use a unique `PGDATA` volume name and container name.
- Change `POSTGRES_DB`, `POSTGRES_USER`, `POSTGRES_PASSWORD` to keep instances isolated.
- If running pgAdmin, change its host port and update `pgadmin-servers.json` accordingly.
- Use namespacing with project flag (example above):

```bash
# namespaced containers, networks, and volumes
docker compose -p pg_staging -f docker-compose-postgres.yml up -d
```

## Security note

This repository contains example passwords in `docker-compose-postgres.yml` (for example: `POSTGRES_PASSWORD`, `PGADMIN_DEFAULT_PASSWORD`). Replace these with environment variables or place them in a local `.env` file and add `.env` to `.gitignore`. A `.env.example` is provided with placeholders. See `postgres/.env.example` for the service-specific variables. Do not mount your `.env` file into containers as a file; instead set these values via environment or your CI/CD provider's secret variables (GitHub/GitLab) when deploying.
