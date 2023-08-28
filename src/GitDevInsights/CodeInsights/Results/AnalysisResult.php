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

    private function __toJson(): string {
        $analysisData = [
            'language-fileext-data' => [],
            'language-global-data' => []
        ];


        foreach ($this->fileExtensionAnalysisResult as $resultTimestamp => $analysisResultRecord) {
            $analysisData['language-fileext-data'][$resultTimestamp] = $analysisResultRecord->getData();
        }

        foreach ($this->languageAnalysisResult as $resultTimestamp => $analysisResultRecord) {
            $analysisData['language-global-data'][$resultTimestamp] = $analysisResultRecord->getData();
        }

        return json_encode($analysisData, JSON_PRETTY_PRINT);
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
