<?php

use GitDevInsights\CodeInsights\Persistence\ProjectConfigDataProvider;
use GitDevInsights\CodeInsights\Results\AnalysisResult;
use GitDevInsights\CodeInsights\Service\CodeInsightsService;

require_once('vendor/autoload.php');

if (isset($argv[1]) && $argv[1] === '--config') {
    $projectConfigFile = $argv[2];

    $jsonOutputPath = 'source-repo/generated-stats/'.time();
    if (isset($argv[3]) && $argv[3] === '--outputPath') {
        $jsonOutputPath = $argv[4];
    }

    $projectConfigDataProvider = new ProjectConfigDataProvider($projectConfigFile);
    $analysisResult = new AnalysisResult();
    $codeInsightsService = new CodeInsightsService($projectConfigDataProvider, $analysisResult);
    $codeInsightsService->analyse(time());

    $analysisResult->outputToJsonFile($jsonOutputPath);
} else {
    echo "Usage: php analyse.php --config [project config file] [--outputPath [path of the generated insights]]\n";
}