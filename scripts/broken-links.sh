#!/bin/bash
set -e
./scripts/build-pages.sh
docker run --rm --network biothermica-site-2026 dcycle/broken-link-checker:3 http://jekyll:4000
