# ownHackathon
Evalutation Project for Hackathons

## Steps for an executable test environment

1. Install `git` and `docker` on your maschine
2. Run `git clone git@github.com:ownHackathon/hackathon-api.git`
3. Optional: Check the configurations in `config/autoload`. In case of changes, copy te file and remove the `.dist` file extension and adjust
   the configuration file.
4. Copy `.env.dist` and rename it `.env` and set correct your userid and groupid. You can find them out in the terminal via commands `id -u && id -g`
5. Run `docker-compose up -d`
6. Run `docker-compose exec php composer install`
7. Run `docker-compose exec php composer run doctrine migrations:sync-metadata-storage`
8. Run `docker-compose exec php composer run doctrine migrations:migrate`
9. Run `docker-compose exec php composer run openapi`

Done. You can now open http://localhost/api/docs/. Thank you and enjoy.

See docker-compose.yml for existing services

# unsupported Script

You will find a script called `hackathon` under `/bin`. This offers possibilities to control the project

- `./bin/hackathon setup` => start the docker container, run composer install and seed Database Data
- `./bin/hackathon start` => start the docker container
- `./bin/hackathon restart` => restart the docker container
- `./bin/hackathon stop` => stop the docker container
- `./bin/hackathon reset` => clean up Database
- `./bin/hackathon reset vendor` => clean up vendor folder
- `./bin/hackathon reset all` => clean up system completely
- `./bin/hackathon composer` => run composer with own param e.g. `./bin/hackathon composer install`
- `./bin/hackathon php` => run commands in php container e.g. `./bin/hackathon php php -v`

