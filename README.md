# Project Title

## Overview

This project aims to build a payment proxy that supports multiple payment providers such as Stripe, PayPal, etc. The main idea is to provide a unified interface for different payment gateways.

## Features

- OAuth authentication through Laravel Passport.
- Vue components for issuing personal tokens and creating new OAuth clients.
- Domain-Driven Design (DDD) approach, leading to some overhead code such as Data Transfer Objects (DTOs) between layers.
- State pattern for transaction state controlling in `app/Services/Transaction/TransactionState.php`.
- Specific transaction state `app/Services/Transaction/States/CompletedState.php` which additionally fires a `PaymentProcessed` event. This event triggers listeners for sending invoice emails and calling webhooks to the client server.
- OpenAPI generation through annotations in the controllers, requests, responses, etc.

## Installation

### Sail Install
```bash
make docker-composer-install 
```

### Database Initialization
```bash
make dev-db-hard-rock && make passport-install
```

## Usage
For OAuth, please refer to the Laravel Passport documentation. After setting up simple auth under the client, call the following request:
localhost/oauth/authorize?client_id=3&redirect_url={xxx}&response_type=code

Where {xxx} is your client server address.

After that run next request that would generate access tokens
```bash
curl --location 'localhost/oauth/token' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'client_id=zzz' \
--data-urlencode 'client_secret=yyy' \
--data-urlencode 'code=xxx' \
--data-urlencode 'grant_type=authorization_code'
```

where:
zzz - client_id from the passport page
yyy - client_secret from the passport page
xxx - code that you got from prev step

## API Documentation
The Swagger API documentation is located in the swagger/openapi_v1.json file.
