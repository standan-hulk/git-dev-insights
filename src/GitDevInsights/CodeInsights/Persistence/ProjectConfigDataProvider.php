<?php

namespace GitDevInsights\CodeInsights\Persistence;

use Symfony\Component\Yaml\Yaml;

/**
 * @immmutable
 */
class ProjectConfigDataProvider {
    public string $repositoryUrl;
    public string $checkoutPath;

    public function __construct(string $configFile) {
        $configData = $this->loadConfig($configFile);

        $this->repositoryUrl = $configData['repository_url'] ?? '';
        $this->checkoutPath = $configData['checkout_path'] ?? '';
    }

    private function loadConfig(string $configFile): array {
        if (!file_exists($configFile)) {
            throw new \InvalidArgumentException("Config file not found: $configFile");
        }

        return Yaml::parseFile($configFile);
    }
}