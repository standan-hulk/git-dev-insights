<?php

namespace GitDevInsights\CodeInsights\Service;

use GitDevInsights\CodeInsights\Analyzer\CodeDistributionFileExtensionAnalyzer;
use GitDevInsights\CodeInsights\Analyzer\CodeDistributionFocusAnalyzer;
use GitDevInsights\CodeInsights\Analyzer\CodeDistributionLanguageAnalyzer;
use GitDevInsights\CodeInsights\Persistence\MappingLanguageDataProvider;
use GitDevInsights\CodeInsights\Persistence\MappingLanguageFocusDataProvider;
use GitDevInsights\CodeInsights\Persistence\ProjectConfigDataProvider;
use GitDevInsights\CodeInsights\Results\AnalysisResult;
use GitDevInsights\FileAnalyzer\Plugins\DirectoryFileAnalyzer;
use GitDevInsights\FileAnalyzer\Plugins\PluginAnalysisResult;
use GitDevInsights\FileAnalyzer\Plugins\PluginManager;

class CodeInsightsService {
    private CONST DATA_PROVIDER_INSIGHTS_LANGUAGE_CONFIG = 'config/code-insights-languages.yaml';

    private CONST DATA_PROVIDER_INSIGHTS_FOCUS_CONFIG = 'config/code-insights-stack-focus.yaml';

    private MappingLanguageDataProvider $languageDataProvider;

    private ProjectConfigDataProvider $projectConfigProvider;

    private MappingLanguageFocusDataProvider $languageFocusDataProvider;

    private PluginManager $pluginManager;

    private AnalysisResult $analysisResult;

    public function __construct(ProjectConfigDataProvider $projectConfigDataProvider, AnalysisResult $analysisResult, PluginManager $pluginManager) {
        $this->languageDataProvider = new MappingLanguageDataProvider(self::DATA_PROVIDER_INSIGHTS_LANGUAGE_CONFIG);
        $this->languageFocusDataProvider = new MappingLanguageFocusDataProvider(self::DATA_PROVIDER_INSIGHTS_FOCUS_CONFIG);
        $this->projectConfigProvider = $projectConfigDataProvider;
        $this->analysisResult = $analysisResult;
        $this->pluginManager = $pluginManager;
    }

    public function analyse(int $currentTimestamp): void {
        $codeDistributionFileExtAnalyzer = new CodeDistributionFileExtensionAnalyzer($this->languageDataProvider, $this->projectConfigProvider->checkoutPath);
        $fileExtensionAnalysisResult = $codeDistributionFileExtAnalyzer->analyzeRepository();

        $codeDistributionLanguageAnalyzer = new CodeDistributionLanguageAnalyzer($this->languageDataProvider, $fileExtensionAnalysisResult);
        $languageAnalysisResult = $codeDistributionLanguageAnalyzer->analyzeByLanguage();

        $codeDistributionLanguageAnalyzer = new CodeDistributionFocusAnalyzer($this->languageFocusDataProvider, $languageAnalysisResult);
        $languageFocusAnalysisResult = $codeDistributionLanguageAnalyzer->analyze();

        $pluginAnalysisResult = $this->performPluginAnalysis();

        $this->analysisResult->addResults($currentTimestamp, $fileExtensionAnalysisResult, $languageAnalysisResult, $languageFocusAnalysisResult, $pluginAnalysisResult);
    }

    /**
     * @return array<string, PluginAnalysisResult>
     */
    private function performPluginAnalysis(): array {

        $allowedExtensions = $this->languageDataProvider->getFileExtensionsStringList();

        $directoryFileAnalyzer = new DirectoryFileAnalyzer($allowedExtensions);
        $matchingFiles = $directoryFileAnalyzer->searchFilesInDirectory($this->projectConfigProvider->checkoutPath);

        $result = [];
        if ($matchingFiles !== []) {
            $result = $this->pluginManager->analyzeFiles($matchingFiles);
        }

        return $result;
    }
}