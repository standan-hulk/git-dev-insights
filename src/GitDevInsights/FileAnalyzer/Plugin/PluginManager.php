<?php

namespace GitDevInsights\FileAnalyzer\Plugin;

final class PluginManager
{
    private array $plugins = [];

    public function registerPlugin(FileAnalyzerPlugin $plugin)
    {
        $this->plugins[] = $plugin;
    }

    public function getPlugins(): array
    {
        return $this->plugins;
    }

    public function analyzeFilesWithPattern(string $pattern)
    {
        $files = glob($pattern);

        foreach ($files as $file) {
            foreach ($this->plugins as $plugin) {
                if ($plugin->canHandleFile($file)) {
                    $plugin->analyzeFile($file);
                }
            }
        }
    }
}
