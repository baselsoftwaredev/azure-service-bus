# How to

## 1. Set Up Development Environment

### 1.1 Install Docker

Easier way of getting this library set up is through docker.

Install docker from the official source [docker.com](https://docs.docker.com/get-docker/)

### 1.2 Build

```
docker compose build
```

### 1.3 Install dependencies

```
docker compose run --rm php composer install
```

### 1.4 Set up env variables for tests

```
docker compose run --rm php composer set-up-development-environment 
```

Update the .env file AZURE_SERVICE_BUS_CONNECTION_STRING variable value to point to one 
your Azure service bus endpoint.

This will make real requests to that endpoint. 
Ensure your subscriptions supports the services that are being tested.

## 2. Run tests 

```
docker compose run --rm php ./vendor/bin/phpunit --configuration phpunit.xml.dist tests
```

## Code Conventions

1. Use PHP type hints as much as possible.
2. Try to avoid `mixed` type.
