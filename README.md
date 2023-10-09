# (Black) Hackathon
Evaluation project for the Hackathon Events on the Discord server from BlackScorp

## Steps for an executable test environment

1. Install `git` and `docker` on your maschine
2. Run `git clone ggit@github.com:ownHackathon/hackathon-api.git`
3. Optional: Check the configurations in `config/autoload`. In case of changes, copy te file and remove the `.dist` file extension and adjust
   the configuration file.
4. Copy `.env.dist` and rename it `.env` and set correct your userid and groupid. You can find them out in the terminal via commands `id -u && id -g`
5. Run `docker-compose up -d`
6. Run `docker-compose exec php composer install`
7. Run `docker-compose exec php composer run doctrine migrations:sync-metadata-storage`
8. Run `docker-compose exec php composer run doctrine migrations:migrate`
9. Run `docker-compose exec php composer run openapi`

Done. You can now open http://localhost/api/doc/ Thanks and have fun.

See docker-compose.yml for existing services
