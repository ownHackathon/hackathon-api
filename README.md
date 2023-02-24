# (Black) Hackathon
Evaluation project for the Hackathon Events on the Discord server from BlackScorp

## Steps for an executable test environment

1. Install `git` and `docker` on your maschine
2. Run `git clone git@github.com:BibaltiK/Hackathon.git`
3. Check the configurations in `config/autoload`. In case of changes, remove the `.dist` file extension and adjust
   the configuration file.
4. Rename `.env.dist` to `.env` and set correct your userid and groupid. You can find them out in the terminal via commands `id -u && id -g`
5. Run `docker-compose up -d`
6. Run `docker-compose exec webserver composer install`
7. Run `docker-compose exec webserver composer run doctrine migrations:sync-metadata-storage`
8. Run `docker-compose exec webserver composer run doctrine migrations:migrate`
