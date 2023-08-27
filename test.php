<?php

require_once('vendor/autoload.php');

use GitDevInsights\CodeInsights\Analyzer\CodeDistributionAnalyzer;
use GitDevInsights\CodeInsights\Persistence\MappingLanguageDataProvider;

// TODO: Programming languages sollten ihre Datei-extensions hosten

$repositoryPath = 'source-repo/data/Star-Confederation';

$codeInsightsLanguageYaml = 'config/code-insights-languages.yaml';

$mappingDataProvider = new MappingLanguageDataProvider($codeInsightsLanguageYaml);
$codeDistributionAnalyzer = new CodeDistributionAnalyzer($mappingDataProvider);

// Analysieren Sie das Repository und erhalten Sie die Code-Verteilung
$codeDistribution = $codeDistributionAnalyzer->analyzeRepository($repositoryPath);

// Ausgabe der Code-Verteilung
print_r($codeDistribution);