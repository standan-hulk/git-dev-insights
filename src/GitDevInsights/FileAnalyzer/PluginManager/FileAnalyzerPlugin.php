<?php

namespace GitDevInsights\FileAnalyzer\PluginManager;

use GitDevInsights\Common\Types\JsonResult;

interface FileAnalyzerPlugin {
    public function canHandleFile(string $filePath): bool;

    public function analyzeFile(string $filePath): JsonResult;
}