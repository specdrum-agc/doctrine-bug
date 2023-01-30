# Test doctrine bug

## Steps to reproduce

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --pull --no-cache` to build fresh images
3. Run `docker compose up -d`
4. Run `docker compose exec php php bin/console app:test` to see error

Run `docker compose exec php php bin/console app:test --restart` to recreate role

Run `docker compose down --remove-orphans` to stop the Docker containers.

