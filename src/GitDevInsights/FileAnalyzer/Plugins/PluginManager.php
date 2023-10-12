<?php

namespace GitDevInsights\FileAnalyzer\Plugins;

final class PluginManager
{
    /**
     * @var FileAnalyzerPlugin[]
     */
    private array $plugins = [];

    public function registerPlugin(FileAnalyzerPlugin $plugin): void
    {
        $this->plugins[] = $plugin;
    }

    public function getPlugins(): array
    {
        return $this->plugins;
    }

    /**
     * @param string[] $files
     */
    public function analyzeFiles(array $files) : array
    {
        $result = [];

        foreach ($files as $fileName) {
            $fileContent = file_get_contents($fileName);

            foreach ($this->plugins as $plugin) {
                if ($plugin->canHandleFile($fileName)) {
                    $jsonResult = $plugin->analyzeFile($fileContent);

                    $result[$jsonResult::class][] = $jsonResult;
                }
            }
        }

        return $result;
    }
}
