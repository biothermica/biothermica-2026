#!/bin/bash
set -e

docker run --rm -v "$(pwd)":/app \
  --entrypoint '/bin/sh' \
  shinsenter/symfony:php8.4-alpine -c 'php /app/scripts/build-pages/run.php'
