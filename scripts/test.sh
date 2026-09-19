#!/bin/bash
set -e
docker compose up -d
sleep 40
# onclick link href
echo "---- onclick link href ----"
./scripts/check-onclick-links.sh

# check broken link href
echo "---- check broken link href ----"
./scripts/broken-links.sh

docker compose down
