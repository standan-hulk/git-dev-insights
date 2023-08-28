<?php

namespace GitDevInsights\CodeInsights\Analyzer;

use GitDevInsights\CodeInsights\Persistence\MappingLanguageDataProvider;
use GitDevInsights\CodeInsights\Results\FileExtensionAnalysisResult;
use GitDevInsights\CodeInsights\Results\LanguageAnalysisResult;

class CodeDistributionLanguageAnalyzer {
    private FileExtensionAnalysisResult $fileExtensionAnalysisResult;
    private MappingLanguageDataProvider $languageDataProvider;

    public function __construct(MappingLanguageDataProvider $languageDataProvider, FileExtensionAnalysisResult $fileExtensionAnalysisResult) {
        $this->languageDataProvider = $languageDataProvider;
        $this->fileExtensionAnalysisResult = $fileExtensionAnalysisResult;
    }

    public function analyzeByLanguage(): LanguageAnalysisResult {
        $result = new LanguageAnalysisResult();

        foreach ($this->fileExtensionAnalysisResult->getData() as $extension => $lines) {
            $languageByExtension = $this->languageDataProvider->findLanguageByExtension($extension);

            if ($languageByExtension === null) {
                continue;
            }

          //  if ($lines > 0) {
                $result->addLanguageLines( $languageByExtension->language->name, $lines);
          //  }
        }

        return $result;
    }
}