# Lumen API

## Start local server

```
php -S localhost:8000 -t public
```

## Change DB_DATABASE=

This has to be changed to absolute_path

## Create database, tables and seed with data

```
php artisan migrate --seed
```

## Endpoints

```
GET /api/v1/ping
GET /api/v2/ping // requires second bearer token

POST /api/v1/presentations // create presentation
GET /api/v1/presentations/{id} // get presentation data by presentation_id
GET /api/v1/presentations/{id}/polls/current // get current presentation poll data by presentation_id
PUT /api/v1/presentations/{id}/polls/current // show next presentation poll
```

## Tokens

Tokens are pre-hardcoded and can be changed in .env file if necessary
