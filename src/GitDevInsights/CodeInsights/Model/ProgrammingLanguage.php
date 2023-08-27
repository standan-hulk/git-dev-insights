<?php

namespace GitDevInsights\CodeInsights\Model;

final class ProgrammingLanguage {
    /**
     * @immutable
     */
    public string $name;

    /**
     * @var ProgrammingLanguageFileExt[]
     */
    public array $fileExtensions;

    public function __construct(string $name) {
        $this->name = $name;
        $this->fileExtensions = [];
    }

    public function addExtension(ProgrammingLanguageFileExt $extension): void
    {
        $this->fileExtensions[$extension->name] = $extension;
    }
}