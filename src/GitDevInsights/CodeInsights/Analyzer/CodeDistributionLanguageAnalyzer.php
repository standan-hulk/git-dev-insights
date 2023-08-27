<?php

namespace GitDevInsights\CodeInsights\Analyzer;

use GitDevInsights\CodeInsights\Persistence\MappingLanguageDataProvider;

class CodeDistributionLanguageAnalyzer {
    private CodeDistributionFileExtensionAnalyzer $fileExtensionAnalyzer;
    private MappingLanguageDataProvider $languageDataProvider;

    public function __construct(MappingLanguageDataProvider $languageDataProvider, CodeDistributionFileExtensionAnalyzer $fileExtensionAnalyzer) {
        $this->languageDataProvider = $languageDataProvider;
        $this->fileExtensionAnalyzer = $fileExtensionAnalyzer;
    }

    /**
     * @return array<string, int>
     */
    public function analyzeByLanguage(): array {
        $fileDistribution = $this->fileExtensionAnalyzer->analyzeRepository();

        $result = [];
        foreach ($fileDistribution as $extension => $lines) {
            $languageByExtension = $this->languageDataProvider->findLanguageByExtension($extension);

            if ($languageByExtension === null) {
                continue;
            }

            $languageName = $languageByExtension->getLanguage();

            if (!isset($result[$languageName])) {
                $result[$languageName] = 0;
            }
            $result[$languageName] += $lines;
        }

        return $result;
    }
}