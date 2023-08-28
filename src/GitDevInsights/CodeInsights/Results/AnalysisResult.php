<?php
namespace GitDevInsights\CodeInsights\Results;

class AnalysisResult
{

    private CONST DATA_PROVIDER_INSIGHTS_OUTPUT_FILE = 'code-insights.json';

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

    private function __toJson(): string {
        $analysisData = [];

        return json_encode($analysisData, JSON_PRETTY_PRINT);
    }

    public function outputToJsonFile(string $outputPath): void {
        $outputFilePath = $outputPath . '/' . self::DATA_PROVIDER_INSIGHTS_OUTPUT_FILE;

        $jsonData = $this->__toJson();

        file_put_contents($outputFilePath, $jsonData);

        echo "Wrote analysis results to '$outputFilePath'." . PHP_EOL;
    }
}
