# Blog Posts API

API REST desenvolvida com Laravel para gerenciamento de posts.

## Tecnologias

- PHP 8.3
- Laravel 10.x
- MySQL 8.0
- Docker & Docker Compose
- Laravel Passport
- Nginx
- PHPUnit

## Arquitetura

O projeto implementa:

- Repository Pattern
- Form Requests para validação
- Middlewares customizados
- Migrations e Seeders
- Testes automatizados
- Docker com configuração automatizada

## Instalação

1. Clone o repositório
```bash
git clone https://github.com/erlancarreira/blogposts_api.git
cd blogposts_api
```

2. Configure o ambiente
  
```bash
cp .env.example .env
```
- (Opcional) Ajuste as variáveis de banco de dados no arquivo .env conforme sua necessidade:

```bash
DB_DATABASE=laravel
DB_USERNAME=blog_posts
DB_PASSWORD=123456
```

3. Inicie os containers
```bash
docker-compose up -d
```

4. Execute o script de configuração
```bash
docker exec -it laravel-cms-container sh -c "./docker-entrypoint.sh"
```

Este comando executará automaticamente:
- Instalação das dependências do Composer
- Configuração dos ambientes (.env e .env.testing)
- Geração e sincronização das chaves de aplicação
- Execução das migrations e seeders
- Instalação e configuração do Passport
- Limpeza dos caches de configuração

## Rate Limiting

A API possui um sistema de limitação de requisições que restringe cada usuário ou IP a um máximo de 60 requisições por minuto.

## Autenticação

A API utiliza token via Laravel Passport. Todas as rotas (exceto login e registro) requerem autenticação.

### Registro de usuário
```http
POST /api/auth/register
Content-Type: application/json

{
    "nome": "Nome",
    "email": "email@exemplo.com",
    "password": "senha",
    "telefone": "99999999"
}
```

### Login
```http
POST /api/auth/login
Content-Type: application/json

{
    "email": "email@exemplo.com",
    "password": "senha"
}
```

### Autenticação de Requests
Adicione o token retornado no login em todas as requisições:
```http
Authorization: Bearer {seu_access_token}
```

## Endpoints

### Autenticação
- POST /api/auth/register - Registro
- POST /api/auth/login - Login
- POST /api/auth/logout - Logout (autenticado)

### Usuários
- GET /api/users - Lista usuários
- GET /api/users/{id} - Obtém usuário
- PUT /api/users/{id} - Atualiza usuário
- DELETE /api/users/{id} - Remove usuário

### Posts
- GET /api/posts - Lista posts
- POST /api/posts - Cria post
- GET /api/posts/{id} - Obtém post
- PUT /api/posts/{id} - Atualiza post
- DELETE /api/posts/{id} - Remove post

## Desenvolvimento

### Estrutura
```
app/
├── Http/
│   ├── Controllers/   
│   ├── Requests/     
│   └── Middleware/   
├── Models/          
├── Repositories/    
├── Interfaces/      
└── Traits/         

database/
├── migrations/     
├── factories/      
└── seeders/       

tests/
├── Unit/
└── Feature/
```

### Containers Docker

- PHP-FPM 8.3
  - Composer integrado
  - Extensões: pdo_mysql, pdo_sqlite, mbstring, exif, pcntl, bcmath, gd
  - Docker entrypoint automatizado para setup inicial
  - Executa como usuário não-root

- Nginx
  - Porta 8080
  - Configurado para Laravel
  - Aguarda PHP-FPM estar saudável

- MySQL 8.0
  - Credenciais via .env
  - Volume persistente para dados
  - Variáveis de ambiente configuráveis

## Documentação

A documentação da API está disponível em:
- Arquivo API Blueprint: `public/docs/api.apib`
- Documentação HTML: `/` (página inicial)
- Documentação HTML alternativa: `public/docs/index.html`

### Testes

O ambiente de testes utiliza SQLite em memória para execução rápida e isolada dos testes. A configuração é feita automaticamente pelo container:
- Copia o arquivo de configuração .env.testing.example
- Sincroniza a chave de criptografia com o ambiente principal
- Configura banco de dados em memória para os testes

Execute os testes com:
```bash
docker-compose exec php php artisan test
```

O projeto inclui:
- Testes de feature para autenticação e autorização
- Testes de CRUD para usuários e posts
- Validação de regras de negócio
- Testes de tratamento de erros
- Factories para geração de dados de teste
- Ambiente isolado para cada execução
