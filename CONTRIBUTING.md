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

### PHP

Use [Laravel Pint](https://laravel.com/docs/pint).

```bash
docker-compose run --rm php ./vendor/bin/pint
```

### SCSS/JavaScript/Markdown/YAML

Use [Prettier](https://prettier.io).

```bash
docker-compose run --rm node npm run prettier -- --write
```

## Running tests

### PHP

```bash
docker-compose run --rm php ./vendor/bin/phpunit
docker-compose run --rm php ./vendor/bin/phpstan analyse --memory-limit=256M
docker-compose run --rm php ./vendor/bin/pint --test
```

### Node.js

```bash
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
