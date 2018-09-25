#!/usr/bin/env bash
export USE_FANCY_INDEX=true
mkdir backup
cp public/index.html backup/index.html
sed -i "s/new Down52PojieCn({/& routerMode: 'history', requestType: 'jsonp' /g" public/index.html

npm install
npm run build

rm dist/index.html
cp LICENSE dist/
cp php/scan.php dist/
cp README.md dist/
zip dist.zip -r dist
rm -rf dist/

cp backup/index.html public/index.html
rm -rf backup/
