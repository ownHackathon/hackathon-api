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
Open the `.env` file and set your `USER_ID` and `GROUP_ID`. This ensures correct file permissions within the container. You can find your IDs by running:
```bash
id -u && id -g
```

### 4. Application Configuration (Optional)
Check the configuration files in `config/autoload/`. If changes are needed:
1. Copy the desired `.dist` file.
2. Remove the `.dist` extension from the copy.
3. Adjust the settings within the new file.

### 5. Quick Setup (Recommended)
We provide a management script to automate the infrastructure start, dependency installation, and database migrations.

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

**Done!** You can now access the API documentation at:
👉 **[http://localhost/api/docs/](http://localhost/api/docs/)**

---

## Unsupported Management CLI via `./bin/hackathon`

The management script consolidates all essential developer commands.

### Usage
Run the script from the project root directory:
```bash
./bin/hackathon [COMMAND]
```

#### Infrastructure Commands
*   **`start`**: Starts the Docker containers in the background.
*   **`stop`**: Pauses the containers (state is preserved).
*   **`down`**: Stops and removes containers and networks.
*   **`setup`**: Complete initial setup (Start, Install, Migrate, OpenAPI).
*   **`openapi`**: Regenerates the API documentation.

#### Reset Functions (Destruction & Rebuild)
*   **`reset`**: Deletes only the database volumes and re-runs migrations.
*   **`reset vendor`**: Deletes both the database **and** the `vendor/` folder for a clean re-installation.
*   **`reset all`**: Deletes everything: database, `vendor/` folder, and local Docker images.

#### Development Utilities (Proxy Commands)
These commands are executed directly inside the running PHP container:
*   **`composer [...]`**: Passes commands through to Composer.
*   *Example:* `./bin/hackathon composer update`
*   **`php [...]`**: Executes PHP commands (automatically prefixes `php`).
*   *Example:* `./bin/hackathon php bin/console debug:router`
*   **`indocker [...]`**: Runs any system command inside the container.
*   *Example:* `./bin/hackathon indocker ls -la`

---

### 💡 Pro-Tip
You can create an alias in your `.bashrc` or `.zshrc` to work even faster:
`alias h='./bin/hackathon'` -> Then simply use `h setup`.
```
