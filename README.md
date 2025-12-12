# Laravel Game Project

Setup moderno de Laravel com Docker, PostgreSQL e Swoole para desenvolvimento de jogos em tempo real.

## Stack

- **PHP 8.3** - Última versão estável
- **Laravel 11.x** - Framework PHP
- **Laravel Octane** - Servidor de aplicação de alta performance
- **Swoole** - Runtime assíncrono para WebSockets
- **PostgreSQL 16** - Banco de dados
- **Redis 7** - Cache e sessões
- **Docker** - Containerização

## Estrutura do Projeto

```
jogo/
├── src/                  # Aplicação Laravel
├── Dockerfile           # Imagem Docker da aplicação
├── docker-compose.yml   # Orquestração dos containers
├── init.sh             # Script de inicialização
└── README.md           # Este arquivo
```

## Instalação Rápida

### 1. Execute o script de inicialização

```bash
chmod +x init.sh
./init.sh
```

Este script irá:
- Instalar o Laravel na pasta `src/`
- Configurar o banco de dados PostgreSQL
- Instalar e configurar o Laravel Octane com Swoole
- Executar as migrations

### 2. Inicie os containers

```bash
docker-compose up -d
```

### 3. Acesse a aplicação

- **HTTP**: http://localhost:8000
- **WebSocket**: ws://localhost:9501
- **PostgreSQL**: localhost:5432
- **Redis**: localhost:6379

## Comandos Úteis

### Gerenciamento de Containers

```bash
# Iniciar containers
docker-compose up -d

# Parar containers
docker-compose down

# Ver logs
docker-compose logs -f app

# Reconstruir containers
docker-compose build --no-cache
```

### Laravel Artisan

```bash
# Acessar container
docker-compose exec app bash

# Executar comandos artisan
docker-compose exec app php artisan migrate
docker-compose exec app php artisan tinker
docker-compose exec app php artisan octane:start
```

### Composer

```bash
# Instalar dependências
docker-compose run --rm app composer install

# Adicionar pacote
docker-compose run --rm app composer require vendor/package
```

## Configuração WebSocket com Swoole

O projeto está configurado para usar Laravel Octane com Swoole, ideal para:

- Comunicação em tempo real
- WebSockets para jogos multiplayer
- Alta performance e baixa latência
- Conexões persistentes

### Exemplo de uso

```php
// Broadcasting events em tempo real
event(new GameEvent($data));
```

## Banco de Dados

### Credenciais padrão

- **Host**: postgres
- **Port**: 5432
- **Database**: laravel_game
- **Username**: laravel
- **Password**: secret

### Executar migrations

```bash
docker-compose exec app php artisan migrate
```

### Criar nova migration

```bash
docker-compose exec app php artisan make:migration create_games_table
```

## Desenvolvimento

### Hot reload

O Octane com Swoole mantém a aplicação em memória. Para recarregar após mudanças:

```bash
docker-compose restart app
```

### Debug

```bash
# Ver logs em tempo real
docker-compose logs -f app

# Acessar PostgreSQL
docker-compose exec postgres psql -U laravel -d laravel_game
```

## Produção

Para ambiente de produção, ajuste:

1. Variáveis de ambiente no `.env`
2. Configure SSL/TLS para WebSockets
3. Ajuste recursos no `docker-compose.yml`
4. Use volumes nomeados para dados persistentes

## Troubleshooting

### Permissões

```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Limpar cache

```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear
```

## Próximos Passos

1. Configure broadcasting para WebSockets
2. Implemente autenticação de usuários
3. Crie models e migrations para o jogo
4. Configure Laravel Echo para comunicação real-time no frontend

## Suporte

Para mais informações, consulte a documentação oficial:
- [Laravel](https://laravel.com/docs)
- [Laravel Octane](https://laravel.com/docs/octane)
- [Swoole](https://www.swoole.co.uk/)
