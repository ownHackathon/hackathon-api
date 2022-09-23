# (Black) Hackathon
Evaluation project for the Hackathon Events on the Discord server from BlackScorp

## Installation

Clone this repository

`git clone https://github.com/BibaltiK/Hackathon.git`

Install composer and node.js/npm and run

`composer install`

and

`npm install`

Build all public files with

`npm run build`

For the execution you need a webserver with database.
The easiest way to do this is to create a docker container using ddev.

Copy config/database.php.dist and rename it to database.php. Then enter the credentials for the database

Rename the `config/autoload/*.dist` files for mail, project and token to `*.php` and then fill in the configurations.

Create Migrations Versions Table with `{ddev} composer doctrine migration:sync-metadata-storage`
and run the Migrations `{ddev} composer doctrine migration:migrate`
