#!/bin/bash
set -e

# Jalankan composer setelah container siap
echo ">> Menjalankan composer install..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Jalankan perintah default (php-fpm)
exec "$@"