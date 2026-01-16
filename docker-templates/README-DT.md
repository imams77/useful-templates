# utemplate — Initialize docker templates into a new project

This repository includes a small CLI `utemplate` (bash) script to copy a chosen docker template into a working project and do small convenience edits (like seeding a `.env`).

Usage

- Initialize a MySQL template into a project folder named `test` and set the DB name:

  utemplate init mysql --project-name test --db-name db_test

Options

- `--project-name` (required) Destination directory name
- `--db-name` (optional) If provided (for mysql), sets `MYSQL_DATABASE` in the created `.env`
- `--output-dir` (optional) Explicit destination path (overrides `--project-name`)
- `--force` (optional) Overwrite destination if it exists

Notes

- The script expects to be run from anywhere; it resolves the templates relative to the `utemplate` script location.
- After running, open the created folder, update `.env` securely, and run your compose up command.
