#!/bin/bash
set -e
./scripts/build-pages.sh

echo "Allowing time to build the site..."
for i in {1..20}; do
    echo "Waiting... ($i/20)"
    sleep 1
done
echo "Finished checking"

docker run --rm dcycle/broken-link-checker:3 http://jekyll
