<?php

namespace GitDevInsights\CodeInsights\Results;

class LanguageAnalysisResult {
    /**
     * @var array<string, int>
     */
    private array $data;

    /**
     * @param array<string, int> $data
     */
    public function __construct(array $data = []) {
        $this->data = $data;
    }

    /**
     * @return array<string, int>
     */
    public function getData(): array {
        return $this->data;
    }

    public function getLanguageLines(string $language): ?int {
        return $this->data[$language] ?? null;
    }

    public function setLanguageLines(string $language, int $lines): void {
        $this->data[$language] = $lines;
    }
}