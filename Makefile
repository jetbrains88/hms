# Variables
COMPOSE=docker compose

.PHONY: up down build watch tinker migrate fresh test logs cache-clear

# Start the environment in the background
up:
	$(COMPOSE) up -d

# Start the environment with Watch mode enabled (Best for dev)
dev:
	$(COMPOSE) up --watch

# Stop all containers
down:
	$(COMPOSE) down

# Rebuild images from scratch
build:
	$(COMPOSE) build --no-cache

fresh-env:
	$(COMPOSE) down -v
	$(COMPOSE) up --build --watch

# Jump into Laravel Tinker
tinker:
	$(COMPOSE) exec app php artisan tinker

# Run migrations manually
migrate:
	$(COMPOSE) exec app php artisan migrate

# Refresh the database (Warning: Wipes data!)
fresh:
	$(COMPOSE) exec app php artisan migrate:fresh --seed

# Run tests (Pest or PHPUnit)
test:
	$(COMPOSE) exec app php artisan test

# View real-time logs
logs:
	$(COMPOSE) logs -f

# Clear all Laravel caches (route, config, cache)
cache-clear:
	$(COMPOSE) exec app php artisan route:clear
	$(COMPOSE) exec app php artisan config:clear
	$(COMPOSE) exec app php artisan cache:clear
	@echo "✅ All caches cleared successfully!"

# Optional: Combined command to clear caches and then run migrations fresh
fresh-clear: cache-clear fresh
	@echo "✅ Caches cleared and database refreshed!"

# Optional: Clear caches and restart containers
restart-clear: down up cache-clear
	@echo "✅ Containers restarted and caches cleared!"