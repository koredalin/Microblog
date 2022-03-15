# Micro blog - backend

## PHP web based API

#### [Wikipedia definition](https://en.wikipedia.org/wiki/One-time_password)

The blog is made by an interview task.

Users are the authors only.
Each author can create, update delete his posts. Also he/she can delete him/herself and his/her posts.

Please, review and the [front end Angular app](https://github.com/koredalin/Microblog-fe-ng).

### Used technologies
* PHP7.4,
* MySQL,
* [Slim framework 3](https://www.slimframework.com/docs/v3/),
* Dependency injection,
* Request/ Response DTO pattern used in the controllers,
* Codeception functional tests,
* Repository pattern for access to the repository services.


INSTALLATION and CONFIGURATION
------------

### Install via Composer

1. Clone the the repo.
2. Next - execute command `composer install` in main folder.
3. Make a database with the [SQL dump file](https://github.com/koredalin/Microblog/blob/master/Common/db_dumps/db_scheme_14X2021.sql).
4. Configure the database from `/config/db.php`.
5. Start the project from \{domain\}/\{project_folder\}/public/index.php


DOCUMENTATION
-------------

- [Roadmap](https://github.com/koredalin/Microblog/blob/master/Common/Docs/roadmap.md)


TESTING
-------

Steps to be reproduced to start the tests.

1. Create a database with name `microblog_test`.
2. Set microblog DB user to work with the database.
3. [//app_root/config/config.php](https://github.com/koredalin/Microblog/blob/master/config/config.php)
	Set _SERVER_ENVIRONMENT_ constant to *DEVELOPMENT_SERVER*. Set _TESTING_MODE_ constant to *true*.
4. You can run the tests with `php vendor/bin/codecept run api` command into the project folder.

Provided tests for:

- `user` table unit tests.
- `post` table functional tests.

**NOTES:**
- Make tests on Development server only.
- Check and edit the other files in the `config/` directory to customize your application as required.
- Don't make tests on production environment.
	- Set a production environment for the live server.
	- Do not make additional database.
	- Do not upload tests folder.
	- Do not upload git folders. They are using too much data storage.
	- And.. - So on...