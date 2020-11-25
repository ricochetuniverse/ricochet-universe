## Code style

### PHP

Follow the [PSR-2](https://www.php-fig.org/psr/psr-2/) code style.

Use [PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) to automatically fix the code style in one command:

```bash
php-cs-fixer fix
```

### SCSS/JavaScript/Markdown/YAML

Use [Prettier](https://prettier.io).

Assuming you have the npm dependencies install, run `yarn run prettier --write` to fix the code style.

## Running tests

Run PHP tests:

```bash
docker-compose run --rm php ./vendor/bin/phpunit
```

Run Node tests:

```bash
docker-compose run --rm node sh -c "yarn run flow && yarn run lint && yarn run test && yarn run prettier -l"
```
