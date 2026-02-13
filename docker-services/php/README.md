# docker-services/php — hybrid PHP runtime services

Purpose: provide "always-on" PHP runtime services with a hybrid strategy: a common `docker-compose.yml` plus small per-version override files that pin images.

Quick start

- Start the default (uses `PHP_IMAGE` or the base image):

  docker compose up -d

- Start PHP 8.4 (pinned override):

  docker compose -f docker-compose.yml -f docker-compose.php8.4.yml up -d

- Run one-off commands in a specific version:

  docker compose -f docker-compose.yml -f docker-compose.php8.2.yml run --rm php composer install

CLI helper

- Add a new version template: `./bin/dockerphp add 8.3`
- Start a pinned version quickly (invokes compose only for the `docker-services/php/` service directory): `./bin/dockerphp start 8.4`  

  Important: dockerphp is intentionally LOCAL — it always operates on the `docker-services/php/` directory and ignores compose files in your current working directory. This prevents accidental starts of unrelated projects and keeps `dockerphp` behavior predictable (unlike the templates which are intended to be sourced/run from arbitrary locations).

  Example (dry-run):

    ./bin/dockerphp start 8.2 --dry-run

  Example (forward compose flags):

    ./bin/dockerphp start 8.4 -- --build

  If you see a "no such service" error, run the dry-run command above — dockerphp will print the merged compose services and the exact command it would run to help you debug.

Reproducibility

- Replace the example `php:X.Y-fpm-alpine` with a fully pinned tag (including patch) in the per-version compose or env file.

Compose spec and warnings

- Modern Docker Compose (v2 / compose specification) ignores the top-level `version:` key and will warn if it's present. That warning is safe but noisy — the fix is to remove the `version:` key from your compose files (the project now follows the Compose specification).
- `dockerphp start` now runs a preflight `docker compose config` and will show the underlying compose validation errors (and list available services) to help debug "no such service" problems.

CI

- Use the per-version compose in your matrix: 
  `docker compose -f docker-compose.yml -f docker-compose.php${{ matrix.php }}.yml run --rm php ...`
