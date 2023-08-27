<?php

namespace GitDevInsights\CodeInsights\Analyzer;

use GitDevInsights\CodeInsights\Persistence\MappingLanguageDataProvider;
use GitDevInsights\CodeInsights\Results\LanguageAnalysisResult;

class CodeDistributionLanguageAnalyzer {
    private CodeDistributionFileExtensionAnalyzer $fileExtensionAnalyzer;
    private MappingLanguageDataProvider $languageDataProvider;

    public function __construct(MappingLanguageDataProvider $languageDataProvider, CodeDistributionFileExtensionAnalyzer $fileExtensionAnalyzer) {
        $this->languageDataProvider = $languageDataProvider;
        $this->fileExtensionAnalyzer = $fileExtensionAnalyzer;
    }

    public function analyzeByLanguage(): LanguageAnalysisResult {
        $fileDistribution = $this->fileExtensionAnalyzer->analyzeRepository();

        $result = new LanguageAnalysisResult();

        foreach ($fileDistribution as $extension => $lines) {
            $languageByExtension = $this->languageDataProvider->findLanguageByExtension($extension);

            if ($languageByExtension === null) {
                continue;
            }

            if ($lines > 0) {
                $result->setLanguageLines( $languageByExtension->language->name, $lines);
            }
        }

        return $result;
    }
}