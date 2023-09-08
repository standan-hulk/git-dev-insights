<?php

namespace GitDevInsights\CodeInsights\Service;

use GitDevInsights\CodeInsights\Analyzer\CodeDistributionFileExtensionAnalyzer;
use GitDevInsights\CodeInsights\Analyzer\CodeDistributionFocusAnalyzer;
use GitDevInsights\CodeInsights\Analyzer\CodeDistributionLanguageAnalyzer;
use GitDevInsights\CodeInsights\Persistence\MappingLanguageDataProvider;
use GitDevInsights\CodeInsights\Persistence\MappingLanguageFocusDataProvider;
use GitDevInsights\CodeInsights\Persistence\ProjectConfigDataProvider;
use GitDevInsights\CodeInsights\Results\AnalysisResult;

class CodeInsightsService {
    private CONST DATA_PROVIDER_INSIGHTS_LANGUAGE_CONFIG = 'config/code-insights-languages.yaml';

    private CONST DATA_PROVIDER_INSIGHTS_FOCUS_CONFIG = 'config/code-insights-fe-be.yaml';

    private MappingLanguageDataProvider $languageDataProvider;

    private ProjectConfigDataProvider $projectConfigProvider;

    private MappingLanguageFocusDataProvider $languageFocusDataProvider;
    public AnalysisResult $analysisResult;

    public function __construct(ProjectConfigDataProvider $projectConfigDataProvider, AnalysisResult $analysisResult) {
        $this->languageDataProvider = new MappingLanguageDataProvider(self::DATA_PROVIDER_INSIGHTS_LANGUAGE_CONFIG);
        $this->languageFocusDataProvider = new MappingLanguageFocusDataProvider(self::DATA_PROVIDER_INSIGHTS_FOCUS_CONFIG);
        $this->projectConfigProvider = $projectConfigDataProvider;
        $this->analysisResult = $analysisResult;
    }

    public function analyse(int $currentTimestamp): void {
        $codeDistributionFileExtAnalyzer = new CodeDistributionFileExtensionAnalyzer($this->languageDataProvider, $this->projectConfigProvider->checkoutPath);
        $fileExtensionAnalysisResult = $codeDistributionFileExtAnalyzer->analyzeRepository();

        $codeDistributionLanguageAnalyzer = new CodeDistributionLanguageAnalyzer($this->languageDataProvider, $fileExtensionAnalysisResult);
        $languageAnalysisResult = $codeDistributionLanguageAnalyzer->analyzeByLanguage();

        $codeDistributionLanguageAnalyzer = new CodeDistributionFocusAnalyzer($this->languageFocusDataProvider, $languageAnalysisResult);
        $languageFocusAnalysisResult = $codeDistributionLanguageAnalyzer->analyze();

        $this->analysisResult->addResults($currentTimestamp, $fileExtensionAnalysisResult, $languageAnalysisResult, $languageFocusAnalysisResult);
    }
}