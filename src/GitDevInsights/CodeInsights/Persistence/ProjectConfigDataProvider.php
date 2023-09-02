<?php

namespace GitDevInsights\CodeInsights\Persistence;

use Symfony\Component\Yaml\Yaml;

/**
 * @immmutable
 */
class ProjectConfigDataProvider {
    public string $repositoryUrl;
    public string $checkoutPath;
    public string $analyseResultPath;

    public string $projectName;

    public function __construct(string $configFile) {
        $configData = $this->loadConfig($configFile);

        $this->repositoryUrl = $configData['repository_url'] ?? '';
        $this->checkoutPath = $configData['checkout_path'] ?? '';
        $this->analyseResultPath = $configData['analyse_result_path'] ?? '';
        $this->projectName = $configData['project_name'] ?? '';
    }

    /**
     * @return array<string, string>
     */
    private function loadConfig(string $configFile): array {
        if (!file_exists($configFile)) {
            throw new \InvalidArgumentException("Config file not found: $configFile");
        }

        return Yaml::parseFile($configFile);
    }
}