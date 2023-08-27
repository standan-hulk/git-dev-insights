<?php

namespace gitDevInsights\CodeInsights\Model;

final class ProgrammingLanguageFileExt {
    private string $extension;
    private ProgrammingLanguage $language;

    public function __construct(string $extension, ProgrammingLanguage $language) {
        $this->extension = $extension;
        $this->language = $language;
    }

    public function getExtension(): string {
        return $this->extension;
    }

    public function getLanguage(): string {
        return $this->language->getName();
    }
}