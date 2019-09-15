# Ricochet Universe

Visit the live website at [https://ricochet.ngyikp.com](https://ricochet.ngyikp.com)

## Server requirements

You can use the provided `docker-compose.yml` to easily set up the server environment. Reading that file is also useful if you want to set it up on bare metal too.

Note that the Docker Compose file is relatively new, there are some known issues such as missing HTTPS and the Content Security Policy being very strict and blocking Laravel Debugbar.

You should set up [queue worker](https://laravel.com/docs/5.7/queues#supervisor-configuration) and set the `QUEUE_DRIVER` to something other than `sync`.

If you want to use the Redis driver for broadcasting/cache/session/queues, you need to have [PhpRedis](https://github.com/phpredis/phpredis) PECL installed.

## Installation for development

After you `git clone` this repo...

1. Add this host to your `hosts` file:
    ```
    127.0.0.1 ricochet.test
    ```
2. Install Docker and Docker Compose
3. Copy `.env.example` to `.env` and adjust your configuration. Note that `DB_USERNAME` and `DB_PASSWORD` will be used as the initial MariaDB user when creating the database container
4. Create a text file on `docker/secrets/mariadb_root_password.txt` that will be your root MariaDB password
5. Open a terminal window and execute these commands:
    ```bash
    docker-compose run --rm composer composer install
    docker-compose run --rm node yarn
    docker-compose run --rm node yarn run development
    docker-compose run --rm composer php artisan migrate
    docker-compose run --rm composer ln -rsTv storage/app/public/ public/storage # php artisan storage:link does not work as it's absolute rather than relative
    ```
6. Execute `docker-compose run --rm node yarn run watch` and leave the terminal window open to rebuild assets whenever you save

The server runs and listens on `http://ricochet.test:8000`

Extra steps:

-   [Laravel IDE Helper](https://github.com/barryvdh/laravel-ide-helper) is installed, you can generate helper files by executing these commands:
    ```bash
    docker-compose run --rm composer php artisan ide-helper:generate
    docker-compose run --rm composer php artisan ide-helper:meta
    ```

## Some useful info

### User-agent

Windows edition:

-   Ricochet Infinity Version 3 Build 62

Mac edition:

-   Ricochet Lost Worlds Version 3 Build 71

### web.archive.org

-   https://web.archive.org/web/20171128131900/http://www.ricochetinfinity.com
-   https://web.archive.org/web/20171128145057/http://www.ricochetinfinity.com:80/levels/index.php

## License

[Mozilla Public License, version 2.0](https://www.mozilla.org/en-US/MPL/2.0/)

Discord and GitLab icons are from the [Simple Icons](https://simpleicons.org) project and licensed under the [CC0 1.0 Universal](https://github.com/simple-icons/simple-icons/blob/develop/LICENSE.md) License.
