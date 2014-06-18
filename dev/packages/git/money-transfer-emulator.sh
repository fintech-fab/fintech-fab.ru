#!/bin/bash

SOURCE="../../../vendor/fintech-fab/money-transfer-emulator/.git"

rm -rf tmp
rm -rf "$SOURCE"
git clone https://github.com/fintech-fab/money-transfer-emulator.git tmp
mv tmp/.git "$SOURCE"
rm -rf tmp