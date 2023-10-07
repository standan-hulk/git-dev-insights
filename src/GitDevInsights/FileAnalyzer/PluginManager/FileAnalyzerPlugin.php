<?php

namespace GitDevInsights\FileAnalyzer\PluginManager;

interface FileAnalyzerPlugin {
    public function canHandleFile(string $filePath): bool;

    public function analyzeFile(string $filePath);
}