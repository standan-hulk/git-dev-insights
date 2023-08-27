<?php

namespace GitDevInsights\CodeInsights\Service;

use GitDevInsights\CodeInsights\Analyzer\CodeDistributionFileExtensionAnalyzer;
use GitDevInsights\CodeInsights\Analyzer\CodeDistributionLanguageAnalyzer;
use GitDevInsights\CodeInsights\Persistence\MappingLanguageDataProvider;
use GitDevInsights\CodeInsights\Persistence\ProjectConfigDataProvider;

class CodeInsightsService {
    private CONST DATA_PROVIDER_INSIGHTS_LANGUAGE_CONFIG = 'config/code-insights-languages.yaml';
    private MappingLanguageDataProvider $languageDataProvider;
    private ProjectConfigDataProvider $projectConfigProvider;

    public function __construct(string $projectConfigFile) {
        $this->languageDataProvider = new MappingLanguageDataProvider(self::DATA_PROVIDER_INSIGHTS_LANGUAGE_CONFIG);
        $this->projectConfigProvider = new ProjectConfigDataProvider($projectConfigFile);
    }

    public function analyse(): void {
        // Analyse der Dateiextensionen
        $codeDistributionFileExtAnalyzer = new CodeDistributionFileExtensionAnalyzer($this->languageDataProvider, $this->projectConfigProvider->checkoutPath);
        $fileExtensionAnalysisResult = $codeDistributionFileExtAnalyzer->analyzeRepository();

        // Analyse der Programmiersprachen
        $codeDistributionLanguageAnalyzer = new CodeDistributionLanguageAnalyzer($this->languageDataProvider, $fileExtensionAnalysisResult);
        $languageAnalysisResult = $codeDistributionLanguageAnalyzer->analyzeByLanguage();

        // Hier können Sie die Ergebnisse weiterverarbeiten oder ausgeben
        dump($fileExtensionAnalysisResult);
        dump($languageAnalysisResult);
    }
}