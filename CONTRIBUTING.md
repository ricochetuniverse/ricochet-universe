## Connecting the game to local site

Ensure the system already has an entry for `ricochet.test` on the `hosts` file.

[Install the certificate authority into the system.](https://github.com/FiloSottile/mkcert#installing-the-ca-on-other-systems)

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
- SCSS/JavaScript/Markdown/YAML: Use [Prettier](https://prettier.io). Run `docker-compose run --rm node npm run prettier -- --write` to auto-fix.

## Type-checking

- Node.js: Use [TypeScript](https://www.typescriptlang.org), previously used [Flow](https://flow.org). Run `docker-compose run --rm node npm run tsc -- -b` to see errors.

## Static analysis

- PHP: Use [PHPStan](https://phpstan.org). Run `docker-compose run --rm php ./vendor/bin/phpstan analyse --memory-limit=256M` to see errors.

## Linting

- Node.js: Use [ESLint](https://eslint.org). Run `docker-compose run --rm node npm run lint` to see errors.

## Tests

- PHP: Use [PHPUnit](https://phpunit.de). Run `docker-compose run --rm php ./vendor/bin/phpunit` to run the tests.
- Node.js: Use [Jest](https://jestjs.io). Run `docker-compose run --rm node npm test` to run the tests.

### Running tests with coverage

```bash
docker-compose run -e XDEBUG_MODE=coverage --rm php ./vendor/bin/phpunit --coverage-html coverage/
```

## Running all tests

The test suites are run on every push, it's best to run them on your system to avoid CI failures afterwards:

```bash
docker-compose run --rm php ./vendor/bin/phpunit
docker-compose run --rm php ./vendor/bin/phpstan analyse --memory-limit=256M
docker-compose run --rm php ./vendor/bin/pint --test
docker-compose run --rm node sh -c "npm run tsc -- -b && npm run lint && npm test && npm run prettier -- -c"
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

Use a service like ngrok or cloudflared to expose your local machine to the public:

```sh
cloudflared tunnel --origin-ca-pool ./docker/secrets/ricochet.test.pem  --http-host-header "ricochet.test:8000" --url https://ricochet.test:8000
```

After the tunnel has set up, change the interaction endpoints URL for the Discord app to something like `https://example.com/api/discord-interactions-webhook`

Open `https://discord.com/oauth2/authorize?client_id=<your app id>&scope=applications.commands&integration_type=1` to install the Discord app to your account.
