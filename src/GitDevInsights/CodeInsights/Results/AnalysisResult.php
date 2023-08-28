<?php
namespace GitDevInsights\CodeInsights\Results;

class AnalysisResult
{

    private CONST DATA_PROVIDER_INSIGHTS_OUTPUT_FILE = 'code-insights.json';

    /**
     * @var array<int, AnalysisResultRecord>
     */
    private array $analysisResults = [];

    public function addResults(int $resultTimestamp, FileExtensionAnalysisResult $fileExtensionAnalysisResult, LanguageAnalysisResult $languageAnalysisResult): void
    {
        $analysisResultRecord = new AnalysisResultRecord($fileExtensionAnalysisResult, $languageAnalysisResult);
        $analysisResultRecord->__toArray();

        $this->analysisResults[$resultTimestamp] = $analysisResultRecord;
    }

    private function __toJson(): string {
        $analysisData = [];

        dump($this->analysisResults);die;

        return json_encode($analysisData, JSON_PRETTY_PRINT);
    }

    public function outputToJsonFile(string $outputPath): void {
        $outputFilePath = $outputPath . '/' . self::DATA_PROVIDER_INSIGHTS_OUTPUT_FILE;

        $jsonData = $this->__toJson();

        file_put_contents($outputFilePath, $jsonData);

        echo "Wrote analysis results to '$outputFilePath'." . PHP_EOL;
    }
}
