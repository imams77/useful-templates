# MongoDB — Installation & Multi-instance Notes 🚀

## Install & Run

### Prerequisites

Docker & Docker Compose

### Start service

```bash
cd /path/to/workspace/templates/docker-db
docker compose -f docker-compose-mongo.yml up -d
```

### Verify

```bash
docker compose -f docker-compose-mongo.yml ps
# or
docker ps | grep mongo
```

### Example: namespaced stack

```bash
docker compose -p mongo_staging -f docker-compose-mongo.yml up -d
```

### Using a per-service `.env`

If you keep a `.env` beside the compose file (`mongo/.env`) either:

- Change directory and run compose:

```bash
cd mongo
docker compose -f docker-compose-mongo.yml up -d
```

- Or pass it explicitly without changing directories:

```bash
docker compose --env-file mongo/.env -f docker-compose-mongo.yml up -d
```

## Adjustments for running multiple instances

- Change host port mapping in `docker-compose-mongo.yml` (e.g. `27017:27017` → `27018:27017`).
- Use a unique `container_name` and volume name for data persistence.
- Use `docker compose -p <project>` (example above) to namespace networks/volumes to avoid conflicts.

## Security note

This repository currently contains example credentials (for example: `MONGO_INITDB_ROOT_PASSWORD`, `ME_CONFIG_MONGODB_ADMINPASSWORD`, `ME_CONFIG_BASICAUTH_PASSWORD`) in `docker-compose-mongo.yml`. Before pushing to GitHub, replace these with environment variables or put them in a local `.env` file and add `.env` to `.gitignore`. A `.env.example` file with placeholders is provided in this folder. See `mongo/.env.example` for the service-specific variables. Do not mount your `.env` file into containers as a file; instead set these values via environment or your CI/CD provider's secret variables (GitHub/GitLab) when deploying.
