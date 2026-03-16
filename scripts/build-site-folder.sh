#!/bin/bash
set -e

mkdir -p docs/_site

docker run --rm \
  -v "$(pwd)/docs":/srv/jekyll \
  jekyll/minimal:3.8 \
  jekyll build
