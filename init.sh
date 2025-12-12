#!/bin/bash

echo "Iniciando setup do Laravel Game Project..."

# Criar diretório src se não existir
if [ ! -d "src" ]; then
    echo "Criando diretório src..."
    mkdir -p src
fi

# Verificar se o Laravel já está instalado
if [ ! -f "src/artisan" ]; then
    echo "Laravel não encontrado. Instalando Laravel..."

    # Usar container temporário para instalar Laravel
    docker run --rm -v $(pwd):/app -w /app composer:latest \
        composer create-project laravel/laravel src --prefer-dist

    echo "Laravel instalado com sucesso!"
else
    echo "Laravel já está instalado!"
fi

# Criar arquivo .env se não existir
if [ ! -f "src/.env" ]; then
    echo "Criando arquivo .env..."
    cp src/.env.example src/.env
fi

# Configurar permissões
echo "Configurando permissões..."
chmod -R 775 src/storage src/bootstrap/cache 2>/dev/null || true

echo "Construindo containers Docker..."
docker-compose build

echo "Instalando Laravel Octane com Swoole..."
docker-compose run --rm app composer require laravel/octane
docker-compose run --rm app php artisan octane:install --server=swoole

echo "Configurando banco de dados..."
docker-compose up -d postgres

# Aguardar PostgreSQL iniciar
echo "Aguardando PostgreSQL iniciar..."
sleep 5

echo "Executando migrations..."
docker-compose run --rm app php artisan migrate --force

echo "Executando seeders..."
docker-compose run --rm app php artisan db:seed

echo ""
echo "Setup concluído com sucesso!"
echo ""
echo "Para iniciar o projeto execute:"
echo "  docker-compose up -d"
echo ""
echo "Para acessar o container:"
echo "  docker-compose exec app bash"
echo ""
echo "Aplicação estará disponível em:"
echo "  - HTTP: http://localhost:8000"
echo "  - WebSocket: ws://localhost:9501"
echo ""
