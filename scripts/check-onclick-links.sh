#!/bin/bash
set -e

echo "=========================================="
echo "Building pages..."
echo "=========================================="

./scripts/build-pages.sh

echo ""
echo "=========================================="
echo "Waiting for Jekyll..."
echo "=========================================="

echo "Allowing time to build the site..."
for i in {1..20}; do
    echo "Waiting... ($i/20)"
    sleep 1
done

echo ""
echo "=========================================="
echo "Checking onclick window.location.href links..."
echo "=========================================="

docker run --rm \
    --network biothermica-site-2026 \
    -v "$(pwd)/docs/_site:/site:ro" \
    -v "$(pwd)/scripts/check-onclick-links.py:/check-onclick-links.py:ro" \
    python:3.12-slim \
    python /check-onclick-links.py \
        /site \
        --base-url http://jekyll:4000/

echo ""
echo "=========================================="
echo "Onclick link check completed successfully!"
echo "=========================================="
