<?php

namespace GitDevInsights\CodeInsights\Analyzer;

use GitDevInsights\CodeInsights\Persistence\MappingLanguageDataProvider;
use GitDevInsights\CodeInsights\Persistence\MappingLanguageFocusDataProvider;
use GitDevInsights\CodeInsights\Results\FileExtensionAnalysisResult;
use GitDevInsights\CodeInsights\Results\LanguageAnalysisResult;
use GitDevInsights\CodeInsights\Results\LanguageFocusAnalysisResult;

class CodeDistributionFocusAnalyzer {
    private LanguageAnalysisResult $languageAnalysisResult;
    private MappingLanguageFocusDataProvider $languageFocusDataProvider;

    public function __construct(MappingLanguageFocusDataProvider $languageFocusDataProvider, LanguageAnalysisResult $languageAnalysisResult) {
        $this->languageFocusDataProvider = $languageFocusDataProvider;
        $this->languageAnalysisResult = $languageAnalysisResult;
    }
    public function analyze(): LanguageFocusAnalysisResult {
        $result = new LanguageFocusAnalysisResult();

        foreach ($this->languageAnalysisResult->getData() as $language => $lines) {
            $focusByLanguage = $this->languageFocusDataProvider->findFocusByProgrammingLanguage($language);

            if ($focusByLanguage === null) {
                continue;
            }

          //  if ($lines > 0) {
                $result->addLines( $focusByLanguage->name, $lines);
          //  }
        }

        return $result;
    }
}