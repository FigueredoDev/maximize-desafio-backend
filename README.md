# Setup Docker Laravel 10 com PHP 8.1

## Passo a passo

Clone Repositório

```sh
git clone https://github.com/FigueredoDev/maximize-desafio-backend
```

```sh
cd maximize-desafio-backend
```

Crie o Arquivo .env

```sh
cp .env.example .env
```

Atualize as variáveis de ambiente do arquivo .env

```dosini
    APP_NAME="Desafio Maximize"
    APP_URL=http://localhost:8989
    APP_KEY=
    APP_DEBUG=false

    DB_CONNECTION=mysql
    DB_HOST=db
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=root
    DB_PASSWORD=root

    CACHE_DRIVER=redis  
    QUEUE_CONNECTION=redis
    SESSION_DRIVER=redis

    REDIS_HOST=redis
    REDIS_PASSWORD=null
    REDIS_PORT=6379
```

Suba os containers do projeto

```sh
docker-compose up -d
```

Instale as dependências do projeto

```sh
docker-compose exec app composer install
```

Execute as migrações

```sh
docker-compose exec app php artisan migrate
```

Gere a key do projeto Laravel

```sh
docker-compose exec app php artisan key:generate
```
