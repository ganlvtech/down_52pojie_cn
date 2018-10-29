#!/usr/bin/env bash
pushd $(dirname $0)
cd ..

export USE_FANCY_INDEX=true
export ASSETS_DIR=.fancyindex

npm install
npm run build

sed -i "s/new Down52PojieCn({/& routerMode: 'history', requestType: 'jsonp' /g" dist/index.html
sed -i "s/new Down52PojieCn({/& routerMode: 'history', requestType: 'jsonp' /g" dist/.fancyindex/footer.html

pushd php/
composer install
popd

cp docs dist/ -R
cp php dist/ -R
rm dist/php/crawl.php
cp LICENSE dist/
cp README.md dist/
zip dist.zip -r dist
rm -rf dist/

popd
