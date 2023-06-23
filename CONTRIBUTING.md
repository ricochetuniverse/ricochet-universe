## Code style

### SCSS/JavaScript/Markdown/YAML

Use [Prettier](https://prettier.io).

Assuming you have the npm dependencies install, run `yarn run prettier --write` to fix the code style.

## Running tests

Run PHP tests:

```bash
docker-compose run --rm php ./vendor/bin/phpunit
docker-compose run --rm php ./vendor/bin/pint
docker-compose run --rm php ./vendor/bin/phpstan analyse --memory-limit=256M
```

Run Node tests:

```bash
docker-compose run --rm node sh -c "yarn run flow && yarn run lint && yarn run test && yarn run prettier -l"
```
