#!/usr/bin/env bash
pushd $(dirname $0)
cd ..

rm -rf dist/
rm dist.zip

rm -rf gh-pages/

popd
