<?php

namespace GitDevInsights\FileAnalyzer\Plugins;

use GitDevInsights\Common\Types\JsonResult;

interface FileAnalyzerInterface {
    public function canHandleFile(string $filePath): bool;

    public function analyzeFile(string $filePath): AnalysisResult;

    public function createEmptyAnalysisResult(): AnalysisResult;

}