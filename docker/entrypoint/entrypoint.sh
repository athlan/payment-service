#!/bin/bash
set -e

#/app/bin/console doctrine:migration:migrate -n

chown -R www-data:www-data /app/var

exec "$@"
