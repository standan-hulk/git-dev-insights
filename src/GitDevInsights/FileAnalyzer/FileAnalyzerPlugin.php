<?php

namespace GitDevInsights\FileAnalyzer;

use GitDevInsights\Common\Types\JsonResult;

class FileAnalyzerPlugin implements FileAnalyzerInterface
{
    protected array $allowedExtensions = [];

    public function canHandleFile(string $filePath): bool
    {
        $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $allowedExtensionsLowercase = array_map('strtolower', $this->allowedExtensions);

        return in_array($fileExtension, $allowedExtensionsLowercase, true);
    }

    public function analyzeFile(string $filePath): JsonResult
    {
        return new JsonResult([]);
    }
}