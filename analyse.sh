#!/bin/bash

php analyse.php --config project-configs/phpmyadmin.yaml --outputPath source-repo/generated-stats/phpmyadmin
# next: analys in Schleife mit Checkout und switch Monat für Monat rückwärts