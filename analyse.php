<?php

use GitDevInsights\CodeInsights\Service\CodeInsightsService;

require_once('vendor/autoload.php');

// Überprüfen, ob der Parameter --config übergeben wurde
if (isset($argv[1]) && $argv[1] === '--config') {
    // Wenn ja, verwenden Sie den nächsten Parameter als Konfigurationsdatei
    $configFile = $argv[2];

    // Übergeben Sie die Konfigurationsdatei an den CodeInsightsService
    $codeInsightsService = new CodeInsightsService($configFile);
    $codeInsightsService->analyse();
} else {
    echo "Verwendung: php analyse.php --config [Konfigurationsdatei]\n";
}