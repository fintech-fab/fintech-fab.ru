#!/bin/bash

SOURCE="../../../vendor/fintech-fab/bank-emulator/.git"

rm -rf tmp
rm -rf "$SOURCE"
git clone https://github.com/fintech-fab/bank-emulator.git tmp
mv tmp/.git "$SOURCE"
rm -rf tmp