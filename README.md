# ownHackathon
Evaluation Project for Hackathons

## Setup: Executable Test Environment

Follow these steps to set up the project on your local machine.

### 1. Prerequisites
Ensure you have `git` and `docker` (including the Docker Compose plugin) installed.

### 2. Clone the Repository
```bash
git clone git@github.com:ownHackathon/hackathon-api.git
cd hackathon-api
```

### 3. Environment Configuration
Copy the environment template and adjust it:
```bash
cp .env.dist .env
```
Open the `.env` file and set your `USERMAP_UID` and `USERMAP_GID`. This ensures correct file permissions within the container. You can find your IDs by running:
```bash
id -u && id -g
```

The most important variables (see `.env.dist` for the full list):

| Variable | Default  | Purpose |
|---|---|---|
| `APP_ENV` | `develop` | Runtime environment; selects the matching `config/autoload/*.{env}.php` files |
| `USERMAP_UID` / `USERMAP_GID` | `1000` / `1000` | Host user/group for file permissions inside the container |
| `HTTP_PORT` / `SHTTP_PORT` | `80` / `443` | Exposed web ports (Apache) |
| `MYSQL_PUBLIC_PORT` | `3306` | Host port of the application database |
| `MYSQL_TESTING_PORT` | `3307` | Host port of the testing database |
| `MYSQL_USER` / `MYSQL_PASSWORD` / `MYSQL_ROOT_PASSWORD` / `MYSQL_DATABASE` | `dev` / `dev` / `root` / `db` | Database credentials |
| `MAILHOG_SMTP_PORT` / `MAILHOG_WEBUI_PORT` | `1025` / `8025` | Mailhog SMTP and Web UI ports |

### 4. Application Configuration (Optional)
Check the configuration files in `config/autoload/`. If changes are needed, copy the desired `.dist` file, remove the extension, and adjust the settings. See [Configuration & Environments](#configuration--environments) for how the files are loaded and what the suffixes (`global`, `develop`, `testing`, `action`, `local`) control.

### 5. Quick Setup (Recommended)
We provide a management script to automate the entire process (infrastructure start, dependency installation, database migrations, and documentation generation).

**When the setup is finished, the script automatically displays a table with all service URLs, ports, and database credentials.**

> [!CAUTION]
> **NOTE:** This script is provided **as-is and unsupported**. Use it at your own risk.

```bash
# Make the script executable
chmod +x bin/hackathon

# Run the automated setup
./bin/hackathon setup
```

*Alternatively, you can run the commands manually:*
<details>
<summary>Show manual steps</summary>

```bash
docker compose up -d
docker compose exec php composer install
docker compose exec php composer run doctrine migrations:sync-metadata-storage
docker compose exec php composer run doctrine migrations:migrate
docker compose exec php composer run openapi
```
</details>

---

## Configuration & Environments

Application configuration lives in `config/autoload/` and is merged by
`config/config.php` using Laminas ConfigAggregator. It loads all files matching
the pattern:

```
config/autoload/{,*.}{global,<APP_ENV>,local}.php
```

The `APP_ENV` environment variable selects the active environment:

| Environment | Set by | Meaning |
|---|---|---|
| `develop` | `.env` (default) | Local development stack |
| `testing` | `phpunit.xml` / `tests/Pest.php` | Test suite |
| `action` | GitHub Actions workflows | Continuous integration |
| `production` | fallback (unset `APP_ENV`) | Production deployment |

### File suffixes / switches

| Suffix | Loaded when | Purpose |
|---|---|---|
| `*.global.php` | always | Shared defaults for every environment (e.g. `cors`, `dependencies`, `logger`, `migrations`, `project`, `routes`, `token`) |
| `*.develop.php` | `APP_ENV=develop` | Local development settings. E.g. `database.develop.php` points to the `database` service, `mail.develop.php` to the Mailhog SMTP server, `logger.develop.php` uses human-readable line logs |
| `*.testing.php` | `APP_ENV=testing` | Test settings. E.g. `database.testing.php` uses the `database-testing` container, `token.testing.php` longer refresh durations |
| `*.action.php` | `APP_ENV=action` | CI settings. E.g. `database.action.php` connects to `127.0.0.1`, `dependencies.action.php` injects `NullMailer`/`NullLogger` mocks |
| `*.production.php` | `APP_ENV=production` (or unset) | Production settings. E.g. `logger.production.php` writes JSON-formatted logs for aggregators |
| `*.local.php` | always | Machine-local overrides (gitignored, see `config/.gitignore`), highest precedence. Use for credentials and local settings |
| `*.dist` | never | Templates only. Copy the file and remove the `.dist` extension to use it |
| `config/development.config.php` | always (loaded last) | Development mode: enables `debug` and disables the config cache. Toggled via `./bin/hackathon composer development-enable/-disable` |

### Precedence

Later files override earlier ones:
`*.global.php` → environment files (`<APP_ENV>`) → `*.local.php`.

### Notes

- `bin/migrations.php` selects its database configuration the same way:
  `config/autoload/database.<APP_ENV>.php`.
- The merged configuration is cached to `data/cache/config-cache.php`. The
  cache is disabled in development; enable it via `config/autoload/local.php`
  and clear it after every deployment with
  `./bin/hackathon composer clear-config-cache` (see
  [Production Deployment](#production-deployment)).

---

## Unsupported Management CLI via `./bin/hackathon`

The management script consolidates all essential developer commands into a single tool.

### Usage
Run the script from the project root directory:
```bash
./bin/hackathon [COMMAND]
```

#### Infrastructure Commands
*   **`start`**: Starts the containers. **Note:** It checks for initialization (vendor/ and volumes) and blocks startup if the project isn't set up yet.
*   **`stop`** / **`down`**: Pauses containers or stops/removes containers and networks.
*   **`setup`**: Complete initial setup (Start, Install, Migrate, OpenAPI, Info).
*   **`services`**: Lists all available service names used in this project.
*   **`logs [svc]`**: Tails logs for all or a specific service (e.g., `./bin/hackathon logs php`).
*   **`logs prune`**: Removes dated log directories older than the configured retention (`logger.retention_days`, default 30) from `data/log`. DSGVO-compliant deletion of account activity logs; the cron scheduling is left to the operator.
*   **`info`**: Displays connectivity info (URLs, Ports, and DB Credentials) for running services.
*   **`openapi`**: Regenerates the API documentation.
*   **`test [unit|integration|file] [options]`**: Runs Pest tests selectively in the PHP container. Without arguments, all tests are executed. Examples:
    *   `./bin/hackathon test unit`: Runs all unit tests.
    *   `./bin/hackathon test integration`: Runs all integration tests.
    *   `./bin/hackathon test tests/Unit/HttpTest.php`: Runs one test file.
    *   `./bin/hackathon test integration WorkspaceCreateTest:'Duplicate workspace'`: Runs a matching test function from a file under `tests/Integration/`.

#### Cleanup & Reset
*   **`clean {docker|app|all}`**:
    *   `docker`: Removes containers, volumes, and **all** project images.
    *   `app`: Removes `vendor/` and all cache files (`.phplint`, `.phpunit`, etc.).
    *   `all`: Performs both docker and app cleanup.
*   **`reset {database|vendor|all}`**:
    *   `database`: Wipes database volumes and re-runs migrations.
    *   `vendor`: Wipes and reinstalls the `vendor/` folder (Database remains untouched).
    *   `all`: Wipes database, vendor, and all caches, followed by a fresh `setup`.

#### Development & Utility Commands
*   **`composer [...]`**: Run Composer commands in the PHP container.
*   **`php [...]`**: Run PHP commands in the PHP container.
*   **`bash`**: Direct interactive shell access to the PHP container.
*   **`mysql`**: Direct access to the MariaDB database console.
*   **`indocker [service] [command]`**: Access any specific container (`php`, `apache`, `database`, `database-testing`, `mailhog`).

---

## Development, Testing & Conventions

The local setup runs in development mode: `.env` sets `APP_ENV=develop`, and
`config/development.config.php` enables the debug flag and disables the
configuration cache so every change is picked up immediately. Additional error
tooling (Whoops error pages) can be toggled with the laminas development mode:

```bash
./bin/hackathon composer development-enable
./bin/hackathon composer development-disable
./bin/hackathon composer development-status
```

### Verifying Changes
Before finishing your work, run the full verification chain:

```bash
./bin/hackathon composer check     # phplint + phpcs + phpstan + pest
./bin/hackathon test unit          # only unit tests
./bin/hackathon test integration   # only integration tests
```

For a coverage run (Xdebug is available inside the PHP container):

```bash
./bin/hackathon php ./vendor/bin/pest --coverage --min=0
```

### Architecture & Conventions
See `docs/contributing/coding_guide.md` for the coding standard and conventions.
The Swagger UI is served at `http://localhost/api/`; regenerate the API
documentation after changing the OpenAPI attributes with
`./bin/hackathon openapi` (output: `public/api/docs/swagger.json`).

---

## Production Deployment

The setup above targets local development. For a production deployment:

1. Set `APP_ENV=production` in the environment of the target host. This prevents
   the local `config/autoload/database.develop.php` from being loaded.
2. Provide the database configuration: copy
   `config/autoload/database.global.php.dist` to
   `config/autoload/database.global.php` and set host, user, password and
   database name. Without it the application starts without a database
   connection. Keep credentials out of version control.
3. Never commit `config/autoload/local.php` – use it only for local overrides.
4. Install dependencies and run the migrations inside the container:

   ```bash
   ./bin/hackathon composer install --no-dev --optimize-autoloader
   ./bin/hackathon composer run doctrine migrations:migrate -- --no-interaction
   ./bin/hackathon openapi
   ```

5. Disable debug mode and enable the configuration cache (see
   `config/autoload/local.php.dist`). After every deploy, clear the cached
   configuration with `./bin/hackathon composer clear-config-cache`.

---

### 💡 Pro-Tip
You can create an alias in your `.bashrc` or `.zshrc` to work even faster:
`alias h='./bin/hackathon'` -> Then simply use `h setup`, `h info`, `h bash` or `h test`.
```
