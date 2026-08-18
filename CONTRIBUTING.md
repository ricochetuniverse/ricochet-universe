# Developing Ricochet Universe

## Server requirements

Use the provided `docker-compose.yml` to easily set up the server environment.

For bare-metal installs:

- [Caddy](https://caddyserver.com)
    - Use Caddy instead of PHP's development server to match production usage as there can be [subtle bugs/differences](https://gitlab.com/ngyikp/ricochet-levels/-/issues/3)
- [PHP 8.5](https://www.php.net)
- [Composer](https://getcomposer.org)
- [MariaDB 12.1](https://mariadb.org)
- [Node.js v22](https://nodejs.org)
- [Valkey](https://valkey.io)
- A [queue worker](https://laravel.com/docs/13.x/queues#supervisor-configuration)
- A [task scheduler](https://laravel.com/docs/13.x/scheduling#running-the-scheduler)

## Installation for development

After you `git clone` this repo...

1. Add this host to your system's `hosts` file:
    ```
    127.0.0.1 ricochet.test
    ```
2. Install Docker and Docker Compose
3. Copy `.env.example` to `.env` and adjust your configuration. Note that `DB_USERNAME` and `DB_PASSWORD` will be used as the initial MariaDB user when creating the database container
4. Create a text file on `docker/secrets/mariadb_root_password.txt` that will be your root MariaDB password
5. Install [mkcert](https://github.com/FiloSottile/mkcert), then run `mkcert -install` and `mkcert ricochet.test`, and move the 2 generated `.pem` files to `docker/secrets/`
6. Open a terminal window and execute these commands:
    ```bash
    docker-compose run --rm php composer install
    docker-compose run --rm node npm install
    docker-compose run --rm node npm run development
    docker-compose run --rm php php artisan migrate
    docker-compose run --rm php php artisan storage:link --relative
    ```

## Day-to-day development

Execute `docker-compose up -d` to start the services, you can access the website at `https://ricochet.test:8000`

Execute `docker-compose run --rm node npm run watch` and leave the terminal window open to rebuild assets whenever you save.

## Connecting the game to local site

First, add an entry for `127.0.0.1 ricochet.test` to your system's `hosts` file.

[Install the certificate authority into your system.](https://github.com/FiloSottile/mkcert#installing-the-ca-on-other-systems)

Edit `Data2.dat` in the Ricochet Infinity game folder to point to the local site.

```
Catalog URL=https://ricochet.test:8000/gateway/catalog.php
```

If `libcurl.dll` cannot be updated for whatever reason, there is a fallback to non-HTTPS.

```
Catalog URL=http://ricochet.test:8001/gateway/catalog.php
```

## Code style

- PHP: Use [Laravel Pint](https://laravel.com/docs/pint). Run `docker-compose run --rm php ./vendor/bin/pint` to auto-fix.
- SCSS/JavaScript/Markdown/YAML: Use [Oxfmt](https://oxc.rs/docs/guide/usage/formatter.html) (previously used [Prettier](https://prettier.io)). Run `docker-compose run --rm node npm run format` to auto-fix.

## Type-checking

- Node.js: Use [TypeScript](https://www.typescriptlang.org) (previously used [Flow](https://flow.org)). Run `docker-compose run --rm node npm run tsc -- -b` to see errors.

## Static analysis

- PHP: Use [PHPStan](https://phpstan.org). Run `docker-compose run --rm php ./vendor/bin/phpstan analyse --memory-limit=320M` to see errors.

## Linting

- Node.js: Use [ESLint](https://eslint.org). Run `docker-compose run --rm node npm run lint` to see errors.

## Tests

- PHP: Use [PHPUnit](https://phpunit.de). Run `docker-compose run --rm php ./vendor/bin/phpunit` to run the tests.
- Node.js: Use [Rstest](https://rstest.rs) (previously used [Jest](https://jestjs.io)). Run `docker-compose run --rm node npm test` to run the tests.

### Running tests with coverage

```bash
docker-compose run -e XDEBUG_MODE=coverage --rm php ./vendor/bin/phpunit --coverage-html coverage/
```

## Running all tests

The test suites are run on every push, it's best to run them on your system to avoid CI failures afterwards:

```bash
docker-compose run --rm php ./vendor/bin/phpunit
docker-compose run --rm php ./vendor/bin/phpstan analyse --memory-limit=320M
docker-compose run --rm php ./vendor/bin/pint --test
docker-compose run --rm node sh -c "npm run tsc -- -b && npm run lint && npm test && npm run format -- --check"
```

## Creating test database

Enter the MariaDB Docker container, use the root password at `docker/secrets/mariadb_root_password.txt`:

```bash
docker-compose exec -it mariadb mariadb -uroot -p
```

Execute MariaDB commands:

```
CREATE DATABASE ricochetlevels_test;
GRANT ALL PRIVILEGES ON ricochetlevels_test.* TO 'ricochetlevels_test'@'%' IDENTIFIED BY '123';
FLUSH PRIVILEGES;
```

## Xdebug

[Xdebug](https://xdebug.org) is already preinstalled on the Docker container.

## Testing Discord interaction webhooks

Use a service like ngrok or cloudflared to expose your local system to the public:

```sh
cloudflared tunnel --origin-ca-pool ./docker/secrets/ricochet.test.pem  --http-host-header "ricochet.test:8000" --url https://ricochet.test:8000
```

After the tunnel has set up, change the interaction endpoints URL for the Discord app to something like `https://example.com/api/discord-interactions-webhook`

Open `https://discord.com/oauth2/authorize?client_id=<your app id>&scope=applications.commands&integration_type=1` to install the Discord app and enable message commands for your account (fixes the "Unknown Integration" error when running the command).

## Old website archive

- https://web.archive.org/web/20171128131900/http://www.ricochetinfinity.com
- https://web.archive.org/web/20171128145057/http://www.ricochetinfinity.com:80/levels/index.php
