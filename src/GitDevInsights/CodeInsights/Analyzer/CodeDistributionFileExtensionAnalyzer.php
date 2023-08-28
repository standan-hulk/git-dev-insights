<?php

namespace GitDevInsights\CodeInsights\Analyzer;

use DirectoryIterator;
use GitDevInsights\CodeInsights\Persistence\MappingLanguageDataProvider;
use GitDevInsights\CodeInsights\Results\FileExtensionAnalysisResult;

class CodeDistributionFileExtensionAnalyzer {
    private MappingLanguageDataProvider $mappingDataProvider;

    /**
     * @var array<string>
     */
    private array $supportedExtensions;

    private FileExtensionAnalysisResult $fileExtensionAnalysisResult;
    private string $repositoryPath;

    public function __construct(MappingLanguageDataProvider $mappingDataProvider, string $repositoryPath) {
        $this->mappingDataProvider = $mappingDataProvider;
        $this->supportedExtensions = $this->initSupportedExtensions();
        $this->repositoryPath = $repositoryPath;
        $this->fileExtensionAnalysisResult = new FileExtensionAnalysisResult();
    }

    /**
     * @return array<string>
     */
    private function initSupportedExtensions(): array {
        $dataProviderFileExtensions = $this->mappingDataProvider->getFileExtensions();

        $result = [];
        foreach ($dataProviderFileExtensions as $fileExtension) {
            $result[] = $fileExtension->name;
        }

        return $result;
    }

    public function analyzeRepository(): FileExtensionAnalysisResult {
        $this->processDirectory($this->repositoryPath);

        return $this->fileExtensionAnalysisResult;
    }

    private function processDirectory(string $path) : void {
        $dir = new DirectoryIterator($path);

        foreach ($dir as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            $filePath = $fileInfo->getPathname();

            if ($fileInfo->isDir()) {
                $this->processDirectory($filePath);
            } elseif ($fileInfo->isFile()) {

                $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

                if (in_array(strtolower($fileExtension), $this->supportedExtensions)) {
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
}
