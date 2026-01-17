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

Reproducibility

- Replace the example `php:X.Y-fpm-alpine` with a fully pinned tag (including patch) in the per-version compose or env file.

CI

- Use the per-version compose in your matrix: 
  `docker compose -f docker-compose.yml -f docker-compose.php${{ matrix.php }}.yml run --rm php ...`
