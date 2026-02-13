# utemplate — Initialize docker templates into a new project

This repository includes a small CLI `utemplate` (bash) script to copy a chosen docker template into a working project and do small convenience edits (like seeding a `.env`).

Usage

- Initialize a MySQL template into a project folder named `test` and set the DB name:

  utemplate init mysql --project-name test --db-name db_test

Options

- `--project-name` (required) Destination directory name
- `--db-name` (optional) If provided (for mysql), sets `MYSQL_DATABASE` in the created `.env`
- `--output-dir` (optional) Explicit destination path (overrides `--project-name`)
- `-n, --compose-name` (optional) Name for the docker-compose file to create in the destination (default: `docker-compose.yml`). If you pass an existing filename that matches one of the template compose files, that file will be used as the source.
- `--force` (optional) Overwrite destination if it exists

Notes

- By default the script will rename the template's `docker-compose-*.yml` to `docker-compose.yml` in the created project. If a `docker-compose.yml` already exists in the destination and you did not pass `--force`, the command will abort to avoid clobbering existing compose files.
- The script will automatically prefix `container_name:` entries in the copied compose file with the project name (for example `myproj_postgres`). If the container name already contains the project name as a prefix it will not be changed.
- The script expects to be run from anywhere; it resolves the templates relative to the `utemplate` script location.
- After running, open the created folder, update `.env` securely, and run your compose up command.
