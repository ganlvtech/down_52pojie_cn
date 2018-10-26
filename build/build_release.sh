#!/usr/bin/env bash
pushd $(dirname $0)
cd ..

export USE_FANCY_INDEX=true

rm -rf dist/

npm install
npm run build

sed -i "s/new Down52PojieCn({/& routerMode: 'history', requestType: 'jsonp' /g" dist/.fancyindex/footer.html

rm dist/index.html
cp php/ dist/ -R
cp LICENSE dist/
cp README.md dist/
zip dist.zip -r dist
rm -rf dist/

popd
