#!/usr/bin/env bash
php php/crawl.php
php php/format.php
npm install
export BUILD_GITHUB_PAGES=true
npm run build
sed -i "s/requestType:\ 'jsonp'/jsonUrl:\ '\/down_52pojie_cn\/list.json'/g" ./dist/index.html
