# lastfmreminder

> Remind users when lastfm hasn't received any songs in the last 24h

## Prerequisites

[Docker Desktop](https://www.docker.com/products/docker-desktop)

## Install

```
git clone git@github.com:alexanderglueck/lastfmreminder.git
cd lastfmreminder
cp .env.example .env
docker-compose up -d
docker exec -it lastfmreminder-app-1 bash
composer install
php artisan key:generate
```

## Add users

To add users crate a `./storage/users.json` file.

The contents should look like this: 

```json
[
    {
        "username": "your lastfm username",
        "email": "your email address"
    }
]
```
