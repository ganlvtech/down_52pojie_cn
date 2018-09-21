#!/usr/bin/env bash
npm install
export VUE_APP_ROUTER_MODE=history
export USE_FANCY_INDEX=true
export FANCY_INDEX_DIR=.fancyindex
npm run build
rm dist/index.html
cp LICENSE dist/
cp php/scan.php dist/
cp README.md dist/
zip dist.zip -r dist
