#!/bin/bash
set -e
docker run --rm --network biothermica-site dcycle/broken-link-checker:3 http://jekyll:4000
