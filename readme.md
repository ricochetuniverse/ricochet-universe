# Ricochet Levels

Visit the live website at [https://ricochet.ngyikp.com](https://ricochet.ngyikp.com)

## Server requirements

-   PHP 7.2.13
-   Composer
-   MariaDB 10.3.12

For production usage, you should set up [queue worker](https://laravel.com/docs/5.6/queues#supervisor-configuration) and set the `QUEUE_DRIVER` to something other than `sync`.

If you want to use the Redis driver for broadcasting/cache/session/queues, you need to have [PhpRedis](https://github.com/phpredis/phpredis) PECL installed.

To compile CSS/JavaScript assets, you need:

-   Node.js
-   Yarn

## Installation for development

After you `git clone` this repo...

1. Ensure you meet the server requirements
2. Open a terminal window and execute `composer install` to install PHP/Composer dependencies
3. Copy `.env.example` to `.env` and adjust your configuration
4. Execute `php artisan migrate` to create the database tables
5. To compile CSS/JavaScript assets:
    1. Execute `yarn` to install the Node dependencies
    2. Execute `yarn run watch` and leave the terminal window open to rebuild whenever you save

Finally, to start the development server, execute `php artisan serve` to listen on port 8000, or `./start_server.sh` to listen on port 80 (may require your root password)

Extra steps:

-   [Laravel IDE Helper](https://github.com/barryvdh/laravel-ide-helper) is installed, you can generate helper files by executing `php artisan ide-helper:generate` and `php artisan ide-helper:meta`

## Some useful info

### User-agent

Ricochet Infinity Version 3 Build 62

### web.archive.org

-   https://web.archive.org/web/20171128131900/http://www.ricochetinfinity.com
-   https://web.archive.org/web/20171128145057/http://www.ricochetinfinity.com:80/levels/index.php

## License

[Mozilla Public License, version 2.0](https://www.mozilla.org/en-US/MPL/2.0/)

Discord and GitLab icons are from the [Simple Icons](https://simpleicons.org) project and licensed under the [CC0 1.0 Universal](https://github.com/simple-icons/simple-icons/blob/develop/LICENSE.md) License.
