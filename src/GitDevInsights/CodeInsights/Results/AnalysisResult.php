<?php
namespace GitDevInsights\CodeInsights\Results;

class AnalysisResult
{
    /**
     * @var array<int, FileExtensionAnalysisResult>
     */
    private array $fileExtensionResults = [];

    /**
     * @var array<int, LanguageAnalysisResult>
     */
    private array $languageResults = [];

    public function addFileExtensionResult(int $resultTimestamp, FileExtensionAnalysisResult $fileExtensionAnalysisResult): void
    {
        $this->fileExtensionResults[$resultTimestamp] = $fileExtensionAnalysisResult;
    }

    public function addLanguageResult(int $resultTimestamp, LanguageAnalysisResult $languageAnalysisResult): void
    {
        $this->languageResults[$resultTimestamp] = $languageAnalysisResult;
    }

    public function __toJson(): string {

    }
}
