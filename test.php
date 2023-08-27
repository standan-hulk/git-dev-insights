<?php

require_once('vendor/autoload.php');

use GitDevInsights\CodeInsights\Analyzer\CodeDistributionFileExtensionAnalyzer;
use GitDevInsights\CodeInsights\Analyzer\CodeDistributionLanguageAnalyzer;
use GitDevInsights\CodeInsights\Persistence\MappingLanguageDataProvider;

// TODO: Programming languages sollten ihre Datei-extensions beinhalten in den Objekten?
// TODO: Mit result Objekten arbeiten

$repositoryPath = 'source-repo/data/php-github-api';

$codeInsightsLanguageYaml = 'config/code-insights-languages.yaml';

$languageDataProvider = new MappingLanguageDataProvider($codeInsightsLanguageYaml);
$codeDistributionAnalyzer = new CodeDistributionFileExtensionAnalyzer($languageDataProvider, $repositoryPath);
$codeDistributionLanguageAnalyzer = new CodeDistributionLanguageAnalyzer($languageDataProvider, $codeDistributionAnalyzer);

dump($codeDistributionLanguageAnalyzer->analyzeByLanguage());diE;
// TODO: mit Resultdaten arbieten
// TODO: checken, ob alle line endings zuverlässig erkannt werden
// Ausgabe der Code-Verteilung
print_r($codeDistribution);