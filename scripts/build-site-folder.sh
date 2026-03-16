#!/bin/bash
set -e

docker run --rm \
  -v "$(pwd)/docs":/srv/jekyll \
  jekyll/minimal:3.8 \
  jekyll build
