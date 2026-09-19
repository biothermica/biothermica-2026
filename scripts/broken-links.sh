set -e

echo "=========================================="
echo "Building pages..."
echo "=========================================="

./scripts/build-pages.sh

echo ""
echo "=========================================="
echo "Allowing time for Jekyll to build the site..."
echo "=========================================="

for i in {1..20}; do
    echo "Waiting... ($i/20)"
    sleep 1
done

echo "Finished waiting"

echo ""
echo "=========================================="
echo "Checking normal links..."
echo "=========================================="

docker run --rm \
    --network biothermica-site-2026 \
    dcycle/broken-link-checker:3 \
    http://jekyll:4000

echo ""
echo "=========================================="
echo "Checking onclick window.location.href links..."
echo "=========================================="

python3 scripts/check-onclick-links.py \
    docs/_site \
    --base-url http://localhost:4312/

echo ""
echo "=========================================="
echo "All broken-link checks passed!"
echo "=========================================="
