<?php

namespace GitDevInsights\FileAnalyzer\Plugins;

use GitDevInsights\Common\Types\JsonResult;

class FileAnalyzerPlugin implements FileAnalyzerInterface
{
    protected array $allowedExtensions = [];

    /**
     * @immmutable
     * @var string
     */
    public string $name = '';

    public function canHandleFile(string $filePath): bool
    {
        $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $allowedExtensionsLowercase = array_map('strtolower', $this->allowedExtensions);

        return in_array($fileExtension, $allowedExtensionsLowercase, true);
    }

    public function analyzeFile(string $filePath): AnalysisResult
    {
        return new AnalysisResult([]);
    }

    public function createEmptyAnalysisResult(): AnalysisResult
    {
        return new AnalysisResult([]);
    }
}