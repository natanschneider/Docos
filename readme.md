# Docos

Docos is a platform for documenting applications and databases. It helps teams keep technical information organized and easy to find.

Built with Laravel and deployed using Docker Compose.

## Features

- Document applications and their technical details
- Document databases, schemas, and related information
- Keep project documentation in one place

> Update this list as features are implemented.

## Requirements

- Docker
- Docker Compose

## Getting started

1. Clone the repository:

   ```bash
   git clone https://github.com/natanschneider/Docos.git
   cd Docos
   ```

2. Create your local environment file:

   ```bash
   cp .env.example .env
   ```

3. Start the services:

   ```bash
   docker compose up -d --build
   ```

4. Install dependencies and prepare Laravel:

   ```bash
   docker compose exec app composer install
   docker compose exec app php artisan key:generate
   docker compose exec app php artisan migrate
   ```

5. Open the application at the address configured in `compose.yaml` (commonly `http://localhost`).

> The service name `app` and the Compose filename may differ in your project. Adjust the commands to match your configuration.

## Common commands

```bash
# Start services
docker compose up -d

# Stop services
docker compose down

# View service logs
docker compose logs -f

# Run database migrations
docker compose exec app php artisan migrate

# Run tests
docker compose exec app php artisan test
```

## Configuration

Set environment-specific values in `.env`. Keep secrets out of version control; commit `.env.example` with placeholder values instead.

## Development

Make changes in the Laravel project files, then run the relevant Artisan commands or tests inside the application container. If you change dependencies or Docker configuration, rebuild the services:

```bash
php artisan serve
```

## Deployment

Configure production environment variables on the server, then build and start the services:

```bash
docker compose up -d --build
```

For production, use strong credentials, avoid exposing database ports publicly, and configure persistent storage and backups for important data.

## Contributing

Contributions are welcome. Open an issue to discuss a change, or submit a pull request with a clear description and relevant tests.

