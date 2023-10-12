<?php

namespace GitDevInsights\FileAnalyzer\Plugins;

interface FileAnalyzerInterface {
    public function canHandleFile(string $filePath): bool;

    public function analyzeFile(string $filePath): PluginAnalysisResult;

    public function createEmptyAnalysisResult(): PluginAnalysisResult;

}