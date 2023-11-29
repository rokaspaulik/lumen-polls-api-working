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

## Launch tests

```
vendor/bin/phpunit
```

## Note

Only V2 routes include proxied results. In V1 some things were implemented differently compared to https://infra.devskills.app/interactive-presentation/api/4.0.0 Oh well... For next times lesson learned - do the proxy part first. (Cause I did in reverse order). As far as that one test goes I was lazy finishing this off so used chatgpt for some part of it (understand what it does, but was lazy remembering syntax, cause at home I don't have PhpStorm with its Intellisense goodies). Api is far from perfect but feel free to ask me questions, because in professional enviroment I would do things a bit differently and offcourse depending on requirements (cause always striving for perfection might be just unnecessary time sink).
