#!/bin/bash
set -e

echo "=========================================="
echo "Building pages..."
echo "=========================================="

./scripts/build-pages.sh

echo ""
echo "=========================================="
echo "Starting Jekyll..."
echo "=========================================="

docker compose up -d jekyll

echo ""
echo "=========================================="
echo "Waiting for Jekyll..."
echo "=========================================="

for i in {1..30}; do
    if curl -fsS http://localhost:4312 > /dev/null 2>&1; then
        echo "Jekyll is ready!"
        break
    fi

    echo "Waiting... ($i/30)"
    sleep 2
done

if ! curl -fsS http://localhost:4312 > /dev/null 2>&1; then
    echo "ERROR: Jekyll did not start."
    docker compose logs jekyll
    exit 1
fi

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

docker compose down
