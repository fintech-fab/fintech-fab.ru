#!/bin/bash

SOURCE="../../../vendor/fintech-fab/catalog/.git"

rm -rf tmp
rm -rf "$SOURCE"
git clone https://github.com/fintech-fab/catalog.git tmp
mv tmp/.git "$SOURCE"
rm -rf tmp