#!/bin/bash
set -e
docker compose up -d
sleep 40
./scripts/broken-links.sh
docker compose down
