<?php

namespace GitDevInsights\CodeInsights\Model;

final class ProgrammingFocus {
    /**
     * @immutable
     */
    public string $name;

    /**
     * @var ProgrammingLanguage[]
     */
    public array $focus;

    public function __construct(string $name) {
        $this->name = $name;
        $this->focus = [];
    }

    public function addLanguage(ProgrammingLanguage $language): void
    {
        $this->focus[strtolower($language->name)] = $language; // TODO: auch bei anderen Relevant?
    }
}