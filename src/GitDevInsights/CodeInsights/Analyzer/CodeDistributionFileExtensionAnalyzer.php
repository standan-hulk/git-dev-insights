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
        $this->supportedExtensions = $this->getSupportedExtensions();
        $this->repositoryPath = $repositoryPath;

        $this->codeFileDistribution = array_fill_keys($this->supportedExtensions, 0);
    }

    /**
     * @return array<string, int>
     */
    public function analyzeRepository(): array {
        $this->processDirectory($this->repositoryPath);

        return $this->codeFileDistribution;
    }

    /**
     * @return array<string>
     */
    private function getSupportedExtensions(): array {
        // Hier könnten Sie die unterstützten Erweiterungen aus dem MappingDataProvider erhalten
        $programmingLanguages = $this->mappingDataProvider->getProgrammingLanguages();

        $supportedExtensions = [];

        foreach ($programmingLanguages as $programmingLanguage) {
            $extensions = $this->mappingDataProvider->findExtensionsByLanguage($programmingLanguage);
            $supportedExtensions = array_merge($supportedExtensions, $extensions);
        }

        return array_unique($supportedExtensions);
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
