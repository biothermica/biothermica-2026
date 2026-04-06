#!/bin/bash
set -e

./scripts/clear.sh

docker run --rm -v "$(pwd)":/app \
  --entrypoint '/bin/sh' \
  shinsenter/symfony:php8.4-alpine -c 'php /app/scripts/build-pages/run.php'
