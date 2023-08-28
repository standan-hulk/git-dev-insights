<?php
namespace GitDevInsights\CodeInsights\Results;

class AnalysisResultRecord
{
    private FileExtensionAnalysisResult $fileExtensionAnalysisResult;

    private LanguageAnalysisResult $languageAnalysisResult;

    public function __construct(FileExtensionAnalysisResult $fileExtensionAnalysisResult, LanguageAnalysisResult $languageAnalysisResult)
    {
        $this->fileExtensionAnalysisResult = $fileExtensionAnalysisResult;
        $this->languageAnalysisResult = $languageAnalysisResult;
    }

    public function __toArray(): array {
        $resultData = [];

        $resultData['file-extension-analysis-data'] = $this->fileExtensionAnalysisResult->getData();
        $resultData['language-analysis-data'] = $this->languageAnalysisResult->getData();

        return $resultData;
    }
}
