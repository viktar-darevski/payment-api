.PHONY: sail
sail:
	@./vendor/bin/sail $(filter-out $@,$(MAKECMDGOALS))

.PHONY: sail-up
sail-up:
	./vendor/bin/sail up

.PHONY: sail-up-d
sail-up-d:
	./vendor/bin/sail up -d

.PHONY: sail-stop
sail-stop:
	./vendor/bin/sail stop

.PHONY: dev-db-hard-rock
dev-db-hard-rock:
	./vendor/bin/sail artisan db:wipe && ./vendor/bin/sail artisan migrate && ./vendor/bin/sail artisan db:seed

.PHONY: docker-composer-install
docker-composer-install:
	docker run --rm --interactive --tty -v ./:/app composer install
