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


.PHONY: generate-openapi-v1
generate-openapi-v1:
	@./vendor/bin/sail php ./vendor/bin/openapi ./app/Http/Resources/V1 ./app/DTO ./app/Http/Requests/Api/V1 ./app/Http/Controllers/Api/V1 -o ./swagger/openapi_v1.json

.PHONY: generate-openapi
generate-openapi: generate-openapi-v1

.PHONY: docker-composer-install
docker-composer-install:
	docker run --rm --interactive --tty -v ./:/app composer install


.PHHONE: passport-install
passport-install:
	./vendor/bin/sail php artisan passport:install
