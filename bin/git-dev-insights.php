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
    shell_exec('git clone ' . $projectConfigDataProvider->repositoryUrl . ' ' . $projectConfigDataProvider->checkoutPath);

    $tsChartTime = strtotime('last Monday');
    $targetDate = date('Y-m-d', $tsChartTime);

    $weekInSeconds = 7 * 24 * 60 * 60;
    $monthInSeconds = $weekInSeconds * 4;

    $pluginManager = new PluginManager();
    $pluginManager->registerPlugin(new JsInlineScriptTagFileAnalyzer());

    $codeInsightsService = new CodeInsightsService($projectConfigDataProvider, $analysisResult, $pluginManager);

    for ($i = 0; $i < $projectConfigDataProvider->timeRangeWeeks; $i++) {
        $codeInsightsService->analyse($tsChartTime);

        $commitHash = shell_exec("cd " . $projectConfigDataProvider->checkoutPath . " && git rev-list -n 1 --before='" . $targetDate . "' HEAD");

        $output = shell_exec("cd " . $projectConfigDataProvider->checkoutPath . " && git checkout " . $commitHash);

        // Commits zwischen den Wochen ermitteln und deren Details erfassen
        $commitData = findCommitsBetweenWeeks($projectConfigDataProvider->checkoutPath, $tsChartTime - $weekInSeconds, $tsChartTime);
dump($commitData);die;
        // Verarbeiten Sie die Commit-Daten hier nach Bedarf

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

    shell_exec('rm -rf ' . escapeshellarg($projectConfigDataProvider->checkoutPath));
} else {
    echo "Usage: php git-dev-insights.php --config [project config file] [--outputPath [path of the generated insights]]\n";
}

// Funktion zum Ermitteln aller Commits zwischen zwei Zeitpunkten
function findCommitsBetweenWeeks($checkoutPath, $startDate, $endDate)
{
    $command = "cd insights/checkout/git-dev-insights && git log --pretty=format:'%h|%an|%ad|%s' --date=short --since='2023-09-04' --until='2023-09-11'";
    exec($command, $output, $returnVar);


// Ausgabe und Rückgabewert anzeigen
    var_dump($output);
    var_dump($returnVar);
    die;

    if ($returnVar === 0) {
        // Erfolgreiche Ausführung, $output enthält die Ausgabe
        foreach ($output as $line) {
            echo $line . "\n";
        }
    } else {
        // Es gab einen Fehler bei der Ausführung des Befehls
        echo "Fehler bei der Ausführung des Befehls.\n";
    }
    die;

    // Alle Commits zwischen den beiden Zeitpunkten anzeigen
    exec("cd $checkoutPath && git log --pretty=format:'%h|%an|%ad|%s' --date=short --since='" . date('Y-m-d', $startDate) . "' --until='" . date('Y-m-d', $endDate) . "'", $commits);
echo "cd $checkoutPath && git log --pretty=format:'%h|%an|%ad|%s' --date=short --since='" . date('Y-m-d', $startDate) . "' --until='" . date('Y-m-d', $endDate) . "'";die;
    dump($commits);die;
    $commitData = [];
    exec("cd $checkoutPath && git log --raw --pretty=format:%h --date=short --since='" . date('Y-m-d', $startDate) . "' --until='" . date('Y-m-d', $endDate) . "'", $commitHashes);

    foreach ($commitHashes as $hash) {
        exec("cd $checkoutPath && git show --stat --pretty=format:'%h|%an|%ad|%s' --date=short $hash", $commitInfo);
        // Verarbeiten Sie $commitInfo, um Autor, Datum, Commit-Nachricht und Statistiken zu extrahieren.
        dump($commitInfo);die;
    }

dump($commits);die;
    // Schleife durch die Commits und erfasse die gewünschten Informationen
    foreach ($commits as $commit) {
        list($hash, $author, $date, $message) = explode('|', $commit);

        // Ermitteln Sie die Anzahl der hinzugefügten und entfernten Zeilen
        exec("cd $checkoutPath && git diff --shortstat $hash^ $hash", $stat);
        $stat = implode("\n", $stat);

        preg_match('/(\d+) insertions\(\+\), (\d+) deletions/', $stat, $matches);

        $addedLines = isset($matches[1]) ? intval($matches[1]) : 0;
        $removedLines = isset($matches[2]) ? intval($matches[2]) : 0;

        // Fügen Sie die Daten zum Array hinzu
        $commitData[] = [
            'commit_hash' => $hash,
            'author' => $author,
            'date' => $date,
            'message' => $message,
            'added_lines' => $addedLines,
            'removed_lines' => $removedLines,
        ];
    }

    // Gib das Commit-Daten-Array zurück
    return $commitData;
}