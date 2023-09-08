<?php

namespace GitDevInsights\CodeInsights\Results;

class LanguageFocusAnalysisResult {
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

    public function addLines(string $languageFocus, int $lines): void {
        if (!isset($this->data[$languageFocus])) {
            $this->data[$languageFocus] = 0;
        }
        $this->data[$languageFocus] = (int)$this->data[$languageFocus] + $lines;
    }
}