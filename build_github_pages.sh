#!/usr/bin/env bash
export BUILD_GITHUB_PAGES=true

npm install
npm run build

sed -i "s/new Down52PojieCn({/& jsonUrl: '\/down_52pojie_cn\/list.json' /g" dist/index.html

php php/github_pages_crawl.php
