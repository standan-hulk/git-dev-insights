<?php

use GitDevInsights\CodeInsights\Persistence\ProjectConfigDataProvider;
use GitDevInsights\CodeInsights\Results\AnalysisResult;
use GitDevInsights\CodeInsights\Service\CodeInsightsService;
use GitDevInsights\CodeInsights\Service\GeneratorFileExtensionChartService;
use GitDevInsights\CodeInsights\Service\GeneratorLanguageChartService;
use GitDevInsights\CodeInsights\Service\GeneratorLanguageStackFocusChartService;
use GitDevInsights\FileAnalyzer\Plugins\Javascript\JsInlineScriptTagFileAnalyzer;
use GitDevInsights\FileAnalyzer\Plugins\PluginManager;
use GitDevInsights\TrendGraph\BasicStatistics\FileExtensionChartHTMLFileGenerator;
use GitDevInsights\TrendGraph\BasicStatistics\FocusChartHTMLFileGenerator;
use GitDevInsights\TrendGraph\BasicStatistics\LanguageChartHTMLFileGenerator;
use GitDevInsights\TrendGraph\CustomStatistics\JsInlineScriptChartFileGenerator;

require_once('vendor/autoload.php');

function calculateProgress(int $currentPosition, int $total): string
{
    if ($total <= 0) {
        return '0%';
    }

    $percentage = ($currentPosition / $total) * 100;
    return round($percentage) . '%';
}


if (isset($argv[1]) && $argv[1] === '--config') {
    $projectConfigFile = $argv[2];

    $projectConfigDataProvider = new ProjectConfigDataProvider($projectConfigFile);
    $analysisResult = new AnalysisResult();

    shell_exec('rm -rf ' . escapeshellarg($projectConfigDataProvider->checkoutPath));
    // checkout the repo
    shell_exec('git clone '.$projectConfigDataProvider->repositoryUrl.' '.$projectConfigDataProvider->checkoutPath);

    $tsChartTime = strtotime('last Monday');
    $targetDate = date('Y-m-d', $tsChartTime);

    $weekInSeconds = 7 * 24 * 60 * 60;
    $monthInSeconds = $weekInSeconds * 4;

    $pluginManager = new PluginManager();
    $pluginManager->registerPlugin(new JsInlineScriptTagFileAnalyzer());

    $codeInsightsService = new CodeInsightsService($projectConfigDataProvider, $analysisResult, $pluginManager);

    for($i = 0; $i < $projectConfigDataProvider->timeRangeWeeks; $i++) {
        $codeInsightsService->analyse($tsChartTime);

        $commitHash = shell_exec("cd ".$projectConfigDataProvider->checkoutPath. " && git rev-list -n 1 --before='". $targetDate ."' HEAD");

        $output = shell_exec("cd ".$projectConfigDataProvider->checkoutPath ." && git checkout ".$commitHash);

        $tsChartTime = $tsChartTime - $weekInSeconds;
        $targetDate = date('Y-m-d', $tsChartTime);

        echo calculateProgress($i, $projectConfigDataProvider->timeRangeWeeks) . "\n";
    }

    $analysisResult->outputToJsonFile($projectConfigDataProvider->analyseResultPath);

    $jsonData = $analysisResult->__toJsonData();

    $fileExtensionChartGenerator = new FileExtensionChartHTMLFileGenerator($analysisResult, $projectConfigDataProvider);
    $fileExtensionChartGenerator->writeChartOutputToFile();

    $languageChartGenerator = new LanguageChartHTMLFileGenerator($analysisResult, $projectConfigDataProvider);
    $languageChartGenerator->writeChartOutputToFile();

    $focusChartGenerator = new GeneratorLanguageStackFocusChartService($analysisResult, $projectConfigDataProvider);
    $focusChartGenerator->generateOutputFile();

    // example usage of very simple custom chart generator - this has to be extended in several ways to make it reusable
    $jsInlineScriptChartFileGenerator = new JsInlineScriptChartFileGenerator($analysisResult, $projectConfigDataProvider);
    $jsInlineScriptChartFileGenerator->writeChartOutputToFile();

    shell_exec('rm -rf ' . escapeshellarg($projectConfigDataProvider->checkoutPath));
} else {
    echo "Usage: php git-dev-insights.php --config [project config file] [--outputPath [path of the generated insights]]\n";
}