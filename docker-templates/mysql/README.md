# MySQL — Installation & Multi-instance Notes 🔧

## Install & Run

### Prerequisites

Docker & Docker Compose

### Start service

```bash
cd /path/to/workspace/templates/docker-db
docker compose -f docker-compose-mysql.yml up -d
```

### Verify & connect

```bash
docker compose -f docker-compose-mysql.yml ps
# Connect with mysql client
mysql -h 127.0.0.1 -P 3306 -u root -p
```

### Example: namespaced stack

```bash
docker compose -p mysql_staging -f docker-compose-mysql.yml up -d
```

### Using a per-service `.env`

If you keep a `.env` beside the compose file (`mysql/.env`) either:

- Change directory and run compose:

```bash
cd mysql
docker compose -f docker-compose-mysql.yml up -d
```

- Or pass it explicitly without changing directories:

```bash
docker compose --env-file mysql/.env -f docker-compose-mysql.yml up -d
```

## Files of interest

- `mysql/my-custom.cnf` — custom MySQL tuning options
- `mysql/init/` — initialization SQL scripts executed on first run

## Adjustments for running multiple instances

- Change host port mapping (e.g. `3306:3306` → `3307:3306`) in compose file.
- Use unique `container_name` and named volume (e.g. `mysql_data_instance2`) to avoid data overlap.
- Update credentials (passwords, DB names) when running separate instances.
- Use project names to namespace resources (example above):

```bash
# namespaced containers, networks, and volumes
docker compose -p mysql_staging -f docker-compose-mysql.yml up -d
```

- Avoid running multiple `phpMyAdmin` on same host port; change its `ports` mapping if present.

## Security note

Check `docker-compose-mysql.yml` for any sample passwords. Use a `.env` file (not committed) or environment variables to keep secrets out of version control. A `.env.example` is provided with placeholders. See `mysql/.env.example` for the service-specific variables. Do not mount your `.env` file into containers as a file; instead set these values via environment or your CI/CD provider's secret variables (GitHub/GitLab) when deploying.
