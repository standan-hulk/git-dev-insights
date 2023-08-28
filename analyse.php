<?php

use GitDevInsights\CodeInsights\Output\LanguageChartHTMLFileGenerator;
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

    // checkout the repo
    shell_exec('git clone '.$projectConfigDataProvider->repositoryUrl);

    $tsChartTime = strtotime('last Monday');
    $targetDate = date('Y-m-d', $tsChartTime);

    $weekInSeconds = 7 * 24 * 60 * 60;
    $monthInSeconds = $weekInSeconds * 4;

    $codeInsightsService = new CodeInsightsService($projectConfigDataProvider, $analysisResult);

    for($i = 0; $i < 50; $i++) {
        $codeInsightsService->analyse($tsChartTime);

        $commitHash = shell_exec("cd ".$projectConfigDataProvider->checkoutPath. " && git rev-list -n 1 --before='". $targetDate ."' HEAD");

        $output = shell_exec("cd ".$projectConfigDataProvider->checkoutPath ." && git checkout ".$commitHash);

        $tsChartTime = $tsChartTime - $monthInSeconds;
        $targetDate = date('Y-m-d', $tsChartTime);
        dump($targetDate);
    }

    $analysisResult->outputToJsonFile($projectConfigDataProvider->analyseResultPath);

    $jsonData = $analysisResult->__toJsonData();
    $htmlOutput = new LanguageChartHTMLFileGenerator($jsonData);

    $html = $htmlOutput->renderChartOutput();

    $fileName = $projectConfigDataProvider->analyseResultPath.'/chart.html';
    $result = $htmlOutput->writeChartOutputToFile($fileName);

} else {
    echo "Usage: php analyse.php --config [project config file] [--outputPath [path of the generated insights]]\n";
}