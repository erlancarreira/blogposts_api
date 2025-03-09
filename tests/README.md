# Testes da API

Este diretório contém os testes automatizados para a API de Blog Posts.

## Estrutura

```
tests/
├── Feature/               # Testes de integração/feature
│   ├── AuthTest.php      # Testes de autenticação
│   ├── PostTest.php      # Testes de posts
│   ├── UserTest.php      # Testes de usuários
│   └── ErrorTest.php     # Testes de tratamento de erros
└── TestCase.php          # Classe base para testes
```

## Executando os Testes

### Todos os Testes

```bash
php artisan test
```

### Testes Específicos

```bash
# Testes de autenticação
php artisan test tests/Feature/AuthTest.php

# Testes de posts
php artisan test tests/Feature/PostTest.php

# Testes de usuários
php artisan test tests/Feature/UserTest.php

# Testes de erros
php artisan test tests/Feature/ErrorTest.php
```

### Opções Úteis

```bash
# Executar testes com informações detalhadas
php artisan test --verbose

# Executar testes em paralelo
php artisan test --parallel

# Parar no primeiro erro
php artisan test --stop-on-failure

# Filtrar testes específicos
php artisan test --filter=test_nome_do_teste
```

## Cobertura dos Testes

Os testes cobrem os seguintes aspectos da API:

### Autenticação
- Login com credenciais válidas/inválidas
- Registro de usuário
- Logout
- Validação de dados

### Posts
- Listagem com paginação
- Filtragem por tags
- Busca por título/conteúdo
- CRUD completo
- Permissões de acesso

### Usuários
- Listagem com paginação
- CRUD completo
- Validações
- Permissões de acesso
- Atualização parcial de dados

### Tratamento de Erros
- Rotas não encontradas
- Acesso não autorizado
- Validação de dados
- JSON inválido
- Métodos HTTP não permitidos
- Permissões insuficientes

## Configuração

Os testes utilizam:
- SQLite em memória para o banco de dados
- Factories para geração de dados
- Sanctum para autenticação
- RefreshDatabase trait para limpar o banco entre os testes

## Boas Práticas

1. Use nomes descritivos em português para os testes
2. Um método de teste por cenário
3. Prepare os dados necessários dentro do próprio teste
4. Utilize os métodos auxiliares do TestCase
5. Limpe os dados após cada teste
6. Verifique tanto a resposta HTTP quanto o estado do banco
7. Use assertões específicas para cada verificação
