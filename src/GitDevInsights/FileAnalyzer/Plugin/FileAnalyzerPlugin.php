<?php

namespace GitDevInsights\FileAnalyzer\Plugin;

interface FileAnalyzerPlugin {
    public function canHandleFile(string $filePath): bool;

    public function analyzeFile(string $filePath);
}