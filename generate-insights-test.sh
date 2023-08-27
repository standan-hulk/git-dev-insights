#!/bin/bash

#rm -rf source-repo/data/Star-Confederation

# Git-Repository klonen
#git clone https://github.com/standan-hulk/star-confederation.git source-repo/data/Star-Confederation

# In das geklonte Verzeichnis wechseln
echo "--- first run";
php test.php
#phpstan-baseline-analyze '*phpstan-baseline.neon' --json > now.json

#echo "--- second run";
#cd source-repo/data/Star-Confederation
#git checkout `git rev-list -n 1 --before="1 week ago" HEAD`
#cd ../../..
#php test.php


#rm -rf test/Star-Confederation