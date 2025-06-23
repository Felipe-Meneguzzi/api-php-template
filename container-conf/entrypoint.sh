#!/bin/bash
set -e

# Verifica se a pasta "vendor" existe
if [ ! -d "/var/www/html/vendor" ]; then
  echo "Pasta vendor não encontrada. Executando composer install..."
  composer install --no-dev --optimize-autoloader
  composer dump-autoload
fi

# Executa o comando padrão (como iniciar o Apache)
exec "$@"
