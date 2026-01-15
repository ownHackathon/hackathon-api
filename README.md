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

### 4. Application Configuration (Optional)
Check the configuration files in `config/autoload/`. If changes are needed, copy the desired `.dist` file, remove the extension, and adjust the settings.

### 5. Quick Setup (Recommended)
We provide a management script to automate the entire process (infrastructure start, dependency installation, database migrations, and documentation generation).

**Once finished, the script automatically displays a table with all service URLs, ports, and database credentials.**

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
docker-compose up -d
docker-compose exec php composer install
docker-compose exec php composer run doctrine migrations:sync-metadata-storage
docker-compose exec php composer run doctrine migrations:migrate
docker-compose exec php composer run openapi
```
</details>

---

## Unsupported Management CLI via `./bin/hackathon`

The management script consolidates all essential developer commands into a single tool.

### Usage
Run the script from the project root directory:
```bash
./bin/hackathon [COMMAND]
```

#### Infrastructure Commands
*   **`start`**: Starts the containers. **Note:** It checks for initialization (vendor/ and volumes) and prevents start if the project isn't set up.
*   **`stop`** / **`down`**: Pauses containers or stops/removes containers and networks.
*   **`setup`**: Complete initial setup (Start, Install, Migrate, OpenAPI, Info).
*   **`services`**: Lists all available service names used in this project.
*   **`logs [svc]`**: Tails logs for all or a specific service (e.g., `./bin/hackathon logs php`).
*   **`info`**: Displays connectivity info (URLs, Ports, and DB Credentials) for running services.
*   **`openapi`**: Regenerates the API documentation.

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
*   **`test [...]`**: Shortcut to run PHPUnit tests (passes arguments to PHPUnit).
*   **`bash`**: Direct interactive shell access to the PHP container.
*   **`mysql`**: Direct access to the MariaDB database console.
*   **`indocker [service] [command]`**: Access any specific container (`php`, `apache`, `database`, `database-testing`, `mailhog`).

---

### 💡 Pro-Tip
You can create an alias in your `.bashrc` or `.zshrc` to work even faster:
`alias h='./bin/hackathon'` -> Then simply use `h setup`, `h info`, `h bash` or `h test`.
```
