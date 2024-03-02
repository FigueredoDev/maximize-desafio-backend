# Setup Docker Laravel 10 com PHP 8.1

## Passo a passo

Clone Repositório

```sh
git clone https://github.com/FigueredoDev/maximize-desafio-backend
```

Acesse a pasta

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

Gere a key do projeto Laravel

```sh
docker-compose exec app php artisan key:generate
```

Execute as migrações

```sh
docker-compose exec app php artisan migrate
```

Criar o Link Simbólico para o Storage
Para servir arquivos de imagem e outros arquivos estáticos corretamente, crie um link simbólico do diretório public/storage para storage/app/public:

```sh
docker-compose exec app php artisan storage:link
```

Executar Migrações e Seeders
Crie as tabelas no banco de dados e popule-as com dados iniciais usando:

```sh
docker-compose exec app php artisan db:seed --class=MateriaTableSeeder
```

## Acesse 

```sh
http://locahost:8989/api/materias
```

## Documentação das Rotas da API

### 1. Listar Todas as Matérias (`GET api/materias`)

Este endpoint retorna uma lista paginada de todas as matérias cadastradas na aplicação. A resposta inclui os principais campos de cada matéria, exceto o `texto_completo`, organizados em páginas para facilitar a navegação e melhorar a performance ao lidar com grandes volumes de dados.

#### Paginação

Os dados são paginados para otimizar o carregamento e a visualização. Cada página contém um número predefinido de matérias, e a resposta inclui metadados para navegação entre as páginas, como `current_page`, `last_page`, `total`, `per_page` e links para `next_page` e `previous_page`.

#### Exemplo de Resposta (JSON):

```json
{
  "data": [
    {
      "id": "uuid-aqui",
      "titulo": "Título da Matéria",
      "descricao": "Descrição breve da matéria.",
      "imagem": "url/para/imagem.jpg",
      "data_de_publicacao": "2023-01-01T00:00:00Z"
    },
    {
      "id": "uuid-aqui-2",
      "titulo": "Outro Título",
      "descricao": "Outra descrição breve.",
      "imagem": "url/para/outra/imagem.jpg",
      "data_de_publicacao": "2023-02-01T00:00:00Z"
    }
  ],
  "links": {
    "first": "http://example.com/api/materias?page=1",
    "last": "http://example.com/api/materias?page=10",
    "prev": null,
    "next": "http://example.com/api/materias?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 10,
    "path": "http://example.com/api/materias",
    "per_page": 15,
    "to": 15,
    "total": 150
  }
}
```

### 2. Obter Matéria por ID (`GET api/materias/{id}`)

Para obter os detalhes de uma matéria específica, incluindo o `texto_completo`, envie uma requisição `GET` para `/materias/{id}`, substituindo `{id}` pelo identificador único da matéria desejada.

#### Exemplo de Resposta (JSON) para Matéria Específica:

```json
{
  "id": "uuid-aqui",
  "titulo": "Título da Matéria",
  "descricao": "Descrição breve da matéria.",
  "imagem": "url/para/imagem.jpg",
  "data_de_publicacao": "2023-01-01T00:00:00Z",
  "texto_completo": "Conteúdo completo da matéria aqui."
}
```

### 3. Criação de Matéria (`DELETE api/materias/{id}`)

Cria uma nova instância de `Materia`.

-   **Método HTTP**: `POST`
-   **URL**: `api/materias`
-   **Corpo da Requisição**: `multipart/form-data` contendo:
    -   `titulo` (string, obrigatório)
    -   `descricao` (string, obrigatório)
    -   `imagem` (file, obrigatório)
    -   `texto_completo` (string, obrigatório)
    -   `data_de_publicacao` (date, obrigatório)
    
### 4. Atualização de `Materia` (`PUT api/materias/{id}`)

Atualiza uma instância específica de `Materia`, excluindo a imagem.

-   **Método HTTP**: `PUT`
-   **URL**: `api/materias/{id}`
-   **Corpo da Requisição**: `multipart/form-data` com campos opcionais (somente os campos a serem atualizados precisam ser enviados):
    -   `titulo` (string, opcional)
    -   `descricao` (string, opcional)
    -   `texto_completo` (string, opcional)
    -   `data_de_publicacao` (date, opcional)

### 5. Atualização da Imagem de `Materia` (`POST api/materias/{id}/imagem`)

Atualiza apenas a imagem de uma instância específica de `Materia`.

-   **Método HTTP**: `POST`
-   **URL**: `api/materias/{id}/imagem`
-   **Corpo da Requisição**: `multipart/form-data` contendo:
    -   `imagem` (file, obrigatório)

### 6. Excluir Matéria (`DELETE api/materias/{id}`)
-   **Método HTTP**: `DELETE`
-   **URL**: `api/materias/{id}`

Para excluir uma matéria específica, envie uma requisição `DELETE` para `/materias/{id}`, substituindo `{id}` pelo identificador único da matéria que deseja excluir. Essa operação não tem corpo de resposta, e um código de status `204 No Content` é tipicamente retornado em caso de sucesso.

