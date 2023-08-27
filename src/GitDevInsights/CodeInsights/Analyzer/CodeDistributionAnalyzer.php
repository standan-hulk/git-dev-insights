<?php

namespace GitDevInsights\CodeInsights\Analyzer;

use GitDevInsights\CodeInsights\Persistence\MappingLanguageDataProvider;

class CodeDistributionAnalyzer {
    private MappingLanguageDataProvider $mappingDataProvider;
    private array $supportedExtensions;
    private array $codeDistribution;

    public function __construct(MappingLanguageDataProvider $mappingDataProvider) {
        $this->mappingDataProvider = $mappingDataProvider;
        $this->supportedExtensions = $this->getSupportedExtensions();
        $this->codeDistribution = array_fill_keys($this->supportedExtensions, 0);
    }

    public function analyzeRepository($repositoryPath): array {
        $this->processDirectory($repositoryPath);

        return $this->codeDistribution;
    }

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

    private function processDirectory($path) : void {
        $dir = new \DirectoryIterator($path);

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
                    $this->codeDistribution[$fileExtension] += count($lines);
                }
            }
        }
    }
}
