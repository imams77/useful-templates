# WordPress — Installation & Multi-instance Notes 🌐

## Install & Run

### Prerequisites

Docker & Docker Compose

### Start stack

```bash
cd /path/to/workspace/templates/docker-db
docker compose -f docker-compose-wordpress.yml up -d
```

### Access

- [WordPress](http://localhost:8080)
- [phpMyAdmin (if enabled)](http://localhost:8081)

### Example: namespaced stack

```bash
docker compose -p wp_staging -f docker-compose-wordpress.yml up -d
```

### Using a per-service `.env`

If you keep a `.env` beside the compose file (`wordpress/.env`) either:

- Change directory and run compose:

```bash
cd wordpress
docker compose -f docker-compose-wordpress.yml up -d
```

- Or pass it explicitly without changing directories:

```bash
docker compose --env-file wordpress/.env -f docker-compose-wordpress.yml up -d
```

## Themes & Plugins

- Place custom themes in `wordpress/themes/` and plugins in `wordpress/plugins/` or use the admin UI / WP-CLI container to install.

## Adjustments for running multiple instances

- Change host port mappings (`8080:80`, `8081:80`) to other free ports (e.g. `8082:80`).
- Use unique `container_name` and named volumes for `wordpress_data` and database volumes.
- When running multiple complete stacks on the same host prefer namespacing via project name (example above).
- Alternatively, edit ports, DB credentials, and `WORDPRESS_DB_HOST` to point to the correct database service if using a shared DB.
- For production parity: enable persistent volumes, configure backups for uploads and DB, set `WP_DEBUG` to `false`, and configure HTTPS using a reverse proxy (Traefik/nginx) or SSL termination.

## Security note

Check `docker-compose-wordpress.yml` for any sample passwords (for example: `MYSQL_ROOT_PASSWORD`, `MYSQL_PASSWORD`, `WORDPRESS_DB_PASSWORD`). Use a `.env` file (not committed) or environment variables to keep secrets out of version control. A `.env.example` is provided with placeholders. See `wordpress/.env.example` for the service-specific variables. Do not mount your `.env` file into containers as a file; instead set these values via environment or your CI/CD provider's secret variables (GitHub/GitLab) when deploying.
