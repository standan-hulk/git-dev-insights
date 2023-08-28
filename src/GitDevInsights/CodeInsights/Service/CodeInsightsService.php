<?php

namespace GitDevInsights\CodeInsights\Service;

use GitDevInsights\CodeInsights\Analyzer\CodeDistributionFileExtensionAnalyzer;
use GitDevInsights\CodeInsights\Analyzer\CodeDistributionLanguageAnalyzer;
use GitDevInsights\CodeInsights\Persistence\MappingLanguageDataProvider;
use GitDevInsights\CodeInsights\Persistence\ProjectConfigDataProvider;
use GitDevInsights\CodeInsights\Results\AnalysisResult;

class CodeInsightsService {
    private CONST DATA_PROVIDER_INSIGHTS_LANGUAGE_CONFIG = 'config/code-insights-languages.yaml';

    private MappingLanguageDataProvider $languageDataProvider;

    private ProjectConfigDataProvider $projectConfigProvider;

    private AnalysisResult $analysisResult;

    public function __construct(string $projectConfigFile) {
        $this->languageDataProvider = new MappingLanguageDataProvider(self::DATA_PROVIDER_INSIGHTS_LANGUAGE_CONFIG);
        $this->projectConfigProvider = new ProjectConfigDataProvider($projectConfigFile);
        $this->analysisResult = new AnalysisResult();
    }

    public function analyse(int $currentTimestamp): void {
        $codeDistributionFileExtAnalyzer = new CodeDistributionFileExtensionAnalyzer($this->languageDataProvider, $this->projectConfigProvider->checkoutPath);
        $fileExtensionAnalysisResult = $codeDistributionFileExtAnalyzer->analyzeRepository();
        $this->analysisResult->addFileExtensionResult($currentTimestamp, $fileExtensionAnalysisResult);

        $codeDistributionLanguageAnalyzer = new CodeDistributionLanguageAnalyzer($this->languageDataProvider, $fileExtensionAnalysisResult);
        $languageAnalysisResult = $codeDistributionLanguageAnalyzer->analyzeByLanguage();
        $this->analysisResult->addLanguageResult($currentTimestamp, $languageAnalysisResult);
    }
}