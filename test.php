<?php

require_once('vendor/autoload.php');

use GitDevInsights\CodeInsights\Analyzer\CodeDistributionFileExtensionAnalyzer;
use GitDevInsights\CodeInsights\Analyzer\CodeDistributionLanguageAnalyzer;
use GitDevInsights\CodeInsights\Persistence\MappingLanguageDataProvider;

// TODO: Programming languages sollten ihre Datei-extensions beinhalten in den Objekten?
// TODO: Mit result Objekten arbeiten

$repositoryPath = 'source-repo/data/phpmyadmin';

$codeInsightsLanguageYaml = 'config/code-insights-languages.yaml';

$languageDataProvider = new MappingLanguageDataProvider($codeInsightsLanguageYaml);

$codeDistributionFileExtAnalyzer = new CodeDistributionFileExtensionAnalyzer($languageDataProvider, $repositoryPath);
$fileExtensionAnalysisResult = $codeDistributionFileExtAnalyzer->analyzeRepository();
dump($fileExtensionAnalysisResult);

$codeDistributionLanguageAnalyzer = new CodeDistributionLanguageAnalyzer($languageDataProvider, $fileExtensionAnalysisResult);
$languageAnalysisResult = $codeDistributionLanguageAnalyzer->analyzeByLanguage();
dump($languageAnalysisResult);die;
