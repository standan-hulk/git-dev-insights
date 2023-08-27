<?php

namespace GitDevInsights\CodeInsights\Analyzer;

use DirectoryIterator;
use GitDevInsights\CodeInsights\Persistence\MappingLanguageDataProvider;

class CodeDistributionFileExtensionAnalyzer {
    private MappingLanguageDataProvider $mappingDataProvider;

    /**
     * @var array<string>
     */
    private array $supportedExtensions;

    /**
     * @var array<string, int>
     */
    private array $codeFileDistribution;
    private string $repositoryPath;

    public function __construct(MappingLanguageDataProvider $mappingDataProvider, string $repositoryPath) {
        $this->mappingDataProvider = $mappingDataProvider;
        $this->supportedExtensions = $this->initSupportedExtensions();
        $this->repositoryPath = $repositoryPath;

        $this->codeFileDistribution = array_fill_keys($this->supportedExtensions, 0);
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

    /**
     * @return array<string, int>
     */
    public function analyzeRepository(): array {
        $this->processDirectory($this->repositoryPath);

        return $this->codeFileDistribution;
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

                if (in_array($fileExtension, $this->supportedExtensions)) {
                    $lines = file($filePath);

                    if (false === $lines) {
                        $linesCounter = 0;
                    } else {
                        $linesCounter = count($lines);
                    }

                    $this->codeFileDistribution[$fileExtension] += $linesCounter;
                }
            }
        }
    }
}
