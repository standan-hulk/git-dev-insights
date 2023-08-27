<?php

require_once('vendor/autoload.php');

use GitDevInsights\CodeInsights\Analyzer\CodeDistributionFileExtensionAnalyzer;
use GitDevInsights\CodeInsights\Analyzer\CodeDistributionLanguageAnalyzer;
use GitDevInsights\CodeInsights\Persistence\MappingLanguageDataProvider;
use GitDevInsights\CodeInsights\Persistence\ProjectConfigDataProvider;

$projectConfigProvider = new ProjectConfigDataProvider('project-configs/phpmyadmin.yaml');

$codeInsightsLanguageYaml = 'config/code-insights-languages.yaml';

$languageDataProvider = new MappingLanguageDataProvider($codeInsightsLanguageYaml);

$codeDistributionFileExtAnalyzer = new CodeDistributionFileExtensionAnalyzer($languageDataProvider, $projectConfigProvider->checkoutPath);
$fileExtensionAnalysisResult = $codeDistributionFileExtAnalyzer->analyzeRepository();
dump($fileExtensionAnalysisResult);

$codeDistributionLanguageAnalyzer = new CodeDistributionLanguageAnalyzer($languageDataProvider, $fileExtensionAnalysisResult);
$languageAnalysisResult = $codeDistributionLanguageAnalyzer->analyzeByLanguage();
dump($languageAnalysisResult);die;
