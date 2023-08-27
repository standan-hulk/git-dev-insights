<?php

namespace GitDevInsights\CodeInsights\Model;

/**
 * @immmutable
 */
final class ProgrammingLanguageFileExt {
    public string $name;
    public ProgrammingLanguage $language;

    public function __construct(string $extension, ProgrammingLanguage $language) {
        $this->name = $extension;
        $this->language = $language;
    }
}