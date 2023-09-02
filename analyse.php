<?php

use GitDevInsights\CodeInsights\Output\LanguageChartHTMLFileGenerator;
use GitDevInsights\CodeInsights\Persistence\ProjectConfigDataProvider;
use GitDevInsights\CodeInsights\Results\AnalysisResult;
use GitDevInsights\CodeInsights\Service\CodeInsightsService;

require_once('vendor/autoload.php');

function calculateProgress($currentPosition, $total) {
    if ($total <= 0) {
        return '0%';
    }

    $percentage = ($currentPosition / $total) * 100;
    return round($percentage) . '%';
}


if (isset($argv[1]) && $argv[1] === '--config') {
    $projectConfigFile = $argv[2];

    $jsonOutputPath = 'source-repo/generated-stats/'.time();
    if (isset($argv[3]) && $argv[3] === '--outputPath') {
        $jsonOutputPath = $argv[4];
    }

    $projectConfigDataProvider = new ProjectConfigDataProvider($projectConfigFile);
    $analysisResult = new AnalysisResult();

    shell_exec('rm -rf ' . escapeshellarg($projectConfigDataProvider->checkoutPath));
    // checkout the repo
    shell_exec('git clone '.$projectConfigDataProvider->repositoryUrl.' '.$projectConfigDataProvider->checkoutPath);

    $tsChartTime = strtotime('last Monday');
    $targetDate = date('Y-m-d', $tsChartTime);

    $weekInSeconds = 7 * 24 * 60 * 60;
    $monthInSeconds = $weekInSeconds * 4;

    $codeInsightsService = new CodeInsightsService($projectConfigDataProvider, $analysisResult);

    $totalWeeks = 150;

    for($i = 0; $i < $totalWeeks; $i++) {
        $codeInsightsService->analyse($tsChartTime);

        $commitHash = shell_exec("cd ".$projectConfigDataProvider->checkoutPath. " && git rev-list -n 1 --before='". $targetDate ."' HEAD");

        $output = shell_exec("cd ".$projectConfigDataProvider->checkoutPath ." && git checkout ".$commitHash);

        $tsChartTime = $tsChartTime - $weekInSeconds;
        $targetDate = date('Y-m-d', $tsChartTime);

        echo calculateProgress($i, $totalWeeks) . "\n";
    }

    $analysisResult->outputToJsonFile($projectConfigDataProvider->analyseResultPath);

    $jsonData = $analysisResult->__toJsonData();
    $htmlOutput = new LanguageChartHTMLFileGenerator($jsonData, $projectConfigDataProvider->projectName);

    $html = $htmlOutput->renderChartOutput();

    $fileName = $projectConfigDataProvider->analyseResultPath.'/chart.html';
    $htmlOutput->writeChartOutputToFile($fileName);

    shell_exec('rm -rf ' . escapeshellarg($projectConfigDataProvider->checkoutPath));
} else {
    echo "Usage: php analyse.php --config [project config file] [--outputPath [path of the generated insights]]\n";
}