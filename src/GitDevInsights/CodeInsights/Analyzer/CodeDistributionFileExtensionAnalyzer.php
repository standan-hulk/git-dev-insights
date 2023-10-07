<?php

namespace GitDevInsights\CodeInsights\Analyzer;

use DirectoryIterator;
use GitDevInsights\CodeInsights\Persistence\MappingLanguageDataProvider;
use GitDevInsights\CodeInsights\Results\FileExtensionAnalysisResult;
use GitDevInsights\FileAnalyzer\Plugins\Javascript\JsInlineScriptTagFileAnalyzer;
use GitDevInsights\FileTools\Service\JsFileUsageFileAnalyzer;

class CodeDistributionFileExtensionAnalyzer
{
    private MappingLanguageDataProvider $mappingDataProvider;

    /**
     * @var array<string>
     */
    private array $supportedExtensions;

    private FileExtensionAnalysisResult $fileExtensionAnalysisResult;
    private string $repositoryPath;

    public function __construct(MappingLanguageDataProvider $mappingDataProvider, string $repositoryPath)
    {
        $this->mappingDataProvider = $mappingDataProvider;
        $this->supportedExtensions = $this->initSupportedExtensions();
        $this->repositoryPath = $repositoryPath;
        $this->fileExtensionAnalysisResult = new FileExtensionAnalysisResult();
        $this->initExtensionLines();
    }

    /**
     * @return array<string>
     */
    private function initSupportedExtensions(): array
    {
        $dataProviderFileExtensions = $this->mappingDataProvider->getFileExtensions();

        $result = [];
        foreach ($dataProviderFileExtensions as $fileExtension) {
            $result[] = $fileExtension->name;
        }

        return $result;
    }

    private function initExtensionLines(): void
    {
        $dataProviderFileExtensions = $this->mappingDataProvider->getFileExtensions();
        foreach ($dataProviderFileExtensions as $fileExtension) {
            $this->fileExtensionAnalysisResult->addFileExtensionLines($fileExtension->name, 0);
        }
    }

    public function analyzeRepository(): FileExtensionAnalysisResult
    {
        $this->processDirectory($this->repositoryPath);

        return $this->fileExtensionAnalysisResult;
    }

    private function processDirectory(string $path): void
    {
        $dir = new DirectoryIterator($path);

        foreach ($dir as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            $filePath = $fileInfo->getPathname();

            if ($fileInfo->isDir()) {
                $this->processDirectory($filePath);
            } elseif ($fileInfo->isFile()) {

                $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                if (in_array($fileExtension, $this->supportedExtensions)) {
                    $this->getDeeperInsights($filePath, $fileExtension);

                    // Todo: hier Tool von Sebastian Bergmann ausprobieren
                    $lines = file($filePath);

                    if (false === $lines) {
                        $linesCounter = 0;
                    } else {
                        $linesCounter = count($lines);
                    }

                    if ($linesCounter > 0) {
                        $this->fileExtensionAnalysisResult->addFileExtensionLines($fileExtension, $linesCounter);
                    }
                }
            }
        }
    }

    private function getDeeperInsights(string $fileName, string $fileExtension): void
    {
        if (JsInlineScriptTagFileAnalyzer::isAllowedToScan($fileExtension)) {
            $jsInlineScriptTagFileAnalyzer = new JsInlineScriptTagFileAnalyzer($fileName);
            $linesCounter = $jsInlineScriptTagFileAnalyzer->countInlineScriptLines();
            $this->fileExtensionAnalysisResult->addFileExtensionLines("__inline_js__", $linesCounter);
        }
    }
}
