#!/bin/bash

SOURCE="../../../vendor/fintech-fab/qiwi-sdk/.git"

rm -rf tmp
rm -rf "$SOURCE"
git clone https://github.com/fintech-fab/qiwi-sdk.git tmp
mv tmp/.git "$SOURCE"
rm -rf tmp