npm install
echo "VUE_APP_ROUTER_MODE=history" > .env.local
echo "USE_FANCY_INDEX=true" >> .env.local
echo "FANCY_INDEX_DIR=.fancyindex" >> .env.local
npm run build
rm .env.local
rm dist/index.html
rm dist/list.json
cp LICENSE dist/
cp php/scan.php dist/
cp README.md dist/
zip dist.zip -r dist
