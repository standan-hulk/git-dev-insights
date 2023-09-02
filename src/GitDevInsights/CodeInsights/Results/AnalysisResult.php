<?php
namespace GitDevInsights\CodeInsights\Results;

use GitDevInsights\CodeInsights\Model\ProgrammingLanguageFileExt;

class AnalysisResult
{

    private CONST DATA_PROVIDER_INSIGHTS_OUTPUT_FILE = 'code-insights.json';

    /**
     * @var fileExtensionAnalysisResult[]
     */
    private array $fileExtensionAnalysisResult = [];

    /**
     * @var ProgrammingLanguageFileExt[]
     */
    private array $languageAnalysisResult = [];

    public function addResults(int $resultTimestamp, FileExtensionAnalysisResult $fileExtensionAnalysisResult, LanguageAnalysisResult $languageAnalysisResult): void
    {
        $this->fileExtensionAnalysisResult[$resultTimestamp] = $fileExtensionAnalysisResult;
        $this->languageAnalysisResult[$resultTimestamp] = $languageAnalysisResult;
    }

    public function __toJson(): string {
        $analysisData = $this->__toJsonData();

        return json_encode($analysisData, JSON_PRETTY_PRINT);
    }

    public function __toJsonData(): array {
        $analysisData = [
            'language-fileext-data' => [],
            'language-global-data' => []
        ];


        foreach ($this->fileExtensionAnalysisResult as $resultTimestamp => $analysisResultRecord) {
            $analysisData['language-fileext-data'][date("Y-m-d", $resultTimestamp)] = $analysisResultRecord->getData();
        }

        foreach ($this->languageAnalysisResult as $resultTimestamp => $analysisResultRecord) {
            $analysisData['language-global-data'][date("Y-m-d", $resultTimestamp)] = $analysisResultRecord->getData();
        }

        return $analysisData;
    }


    public function outputToJsonFile(string $outputPath): void {
        // Check if the output path is a valid directory
        if (!is_dir($outputPath)) {
            // If not, attempt to create the directory
            if (!mkdir($outputPath, 0777, true)) {
                echo "Error: Failed to create the output directory." . PHP_EOL;
                return;
            }
        }

        $outputFilePath = $outputPath . '/' . self::DATA_PROVIDER_INSIGHTS_OUTPUT_FILE;

        $jsonData = $this->__toJson();

        file_put_contents($outputFilePath, $jsonData);

        echo "Analysis results written to '$outputFilePath'." . PHP_EOL;
    }
}
