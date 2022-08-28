# (Black) Hackathon
Evaluation project for the Hackathon Events on the Discord server from BlackScorp

## Installation

Clone this Repository

`git clone https://github.com/BibaltiK/Hackathon.git`

Install composer an node.js/npm and run

`composer install`

and

`npm install`

Build all public files with

`npm run build`

Copy config/database.dist.php and rename it to database.php. After that fill in your database connection credentials

import Database structure `database/structure/structure.sql` and Data `database/data/live.sql`

copy `config/autoload/*.dist` for `mail`, `project` and `token` After that fill the configurations.
