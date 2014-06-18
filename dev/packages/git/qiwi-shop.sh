#!/bin/bash

SOURCE="../../../vendor/fintech-fab/qiwi-shop/.git"

rm -rf tmp
rm -rf "$SOURCE"
git clone https://github.com/fintech-fab/qiwi-shop.git tmp
mv tmp/.git "$SOURCE"
rm -rf tmp